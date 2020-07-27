<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpreadsheetRequest;
use App\Repositories\SpreadsheetRepository;
use App\Services\SpreadsheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetController extends Controller
{
    private $spreadsheetRepo;

    public function __construct(SpreadsheetRepository $spreadsheetRepo)
    {
        $this->spreadsheetRepo = $spreadsheetRepo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $awardId = $request->get('premiado_id');
        $spreadsheet = $this->spreadsheetRepo->getData($id, $awardId);
        return view('spreadsheets.edit', compact('spreadsheet', 'awardId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SpreadsheetRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $this->spreadsheetRepo->save($data, $id);

        $spreadsheetTotal = (float) \App\Spreadsheet::select(DB::raw('sum(spreadsheet_value) as spreadsheet_total'))
            ->where('spreadsheet_award_id', $request->spreadsheet_award_id)
            ->whereNull('spreadsheet_chargeback')
            ->first()
            ->spreadsheet_total;

        \App\Award::where('id', $request->spreadsheet_award_id)
            ->update([
                'awarded_value' => $spreadsheetTotal
            ]);

        $service = new SpreadsheetService($this->spreadsheetRepo);
        $service->updateSpreadsheet($data, $id);

        return redirect()->route('admin.home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $spreadsheet = $this->spreadsheetRepo->getSpreadsheetFileAndSpreadsheetKeyLine($id);

        $excel = IOFactory::load($spreadsheet->awarded_upload_table);

        $excel->getActiveSheet()->removeRow($spreadsheet->spreadsheet_keyline);

        $writer = new Xlsx($excel);
        $writer->save($spreadsheet->awarded_upload_table);

        $this->spreadsheetRepo->delete($id);
        return redirect()->route('admin.register.awardeds.show', compact('id'));
    }
}
