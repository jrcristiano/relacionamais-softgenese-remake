<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Facades\Itau;
use App\Repositories\AwardRepository as AwardRepo;
use App\Repositories\SpreadsheetRepository as SpreadsheetRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShipmentControllerApi extends Controller
{
    private $spreadsheetRepo;
    private $awardRepo;

    public function __construct(SpreadsheetRepo $spreadsheetRepo, AwardRepo $awardRepo)
    {
        $this->spreadsheetRepo = $spreadsheetRepo;
        $this->awardRepo = $awardRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->get('data');
        $models = [];
        foreach ($data as $key => $id) {
            $models[] = $this->spreadsheetRepo->getShipments($id);

            $lastField = \App\ShipmentApi::select('shipment_last_field')
                ->orderBy('shipment_last_field', 'desc')
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->first();

            $setLastField = !$lastField ? 1 : $lastField->shipment_last_field + 1;
            $fields[] = $setLastField;
            $identifies[] = $id;
        }

        $itau = new Itau;

        foreach ($models as $key => $values) {
            foreach ($values as $key => $value) {
                $newField[] = $value;
            }
        }

        $itau->setFields($newField);

        $rows = $itau->run();

        $rows = $itau->makeFinallyRows($rows);

        $filename = '';
        foreach ($fields as $key => $field) {
            $date = Carbon::parse(Carbon::now())->format('dm');
            $field = str_pad($field, 2, '0', STR_PAD_LEFT);

            $filename = "R{$date}{$field}.txt";

            \App\ShipmentApi::create([
                'shipment_award_id' => $identifies[$key],
                'shipment_last_field' => $field,
                'shipment_generated' => 1,
                'shipment_file' => $filename
            ]);
        }

        foreach ($identifies as $key => $id) {
            $demandId = \App\Award::select('awarded_demand_id')
                ->where('id', $id)
                ->first()
                ->awarded_demand_id;

            \App\CashFlow::create([
                'flow_movement_date' => date('Y-m-d'),
                'flow_bank_id' => 1,
                'flow_award_id' => $id,
                'flow_award_generated_shipment' => date('Y-m-d'),
                'flow_demand_id' => $demandId,
            ]);
       }

        $path = storage_path('app/public/shipments');
        $storageFileName = "{$path}/{$filename}";

        $itau->makeFileText($rows, $storageFileName);

        $filename = "/{$filename}";
        return $filename;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'awarded_shipment_cancelled' => 'boolean'
        ]);

        $this->awardRepo->save($data, $id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
