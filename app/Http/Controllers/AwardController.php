<?php

namespace App\Http\Controllers;

use App\Http\Requests\AwardRequest;
use App\Repositories\{AwardRepository as AwardRepo,
    CashFlowRepository as CashFlowRepo,
    NoteRepository,
    SpreadsheetRepository as SpreadsheetRepo
};
use App\Services\SpreadsheetService;
use App\Uploads\Facades\Upload;
use App\Repositories\BankRepository as BankRepo;

class AwardController extends Controller
{
    private $awardRepo;
    private $spreadsheetRepo;
    private $spreadsheetService;
    private $bankRepo;
    private $cashFlowRepo;

    public function __construct(AwardRepo $awardRepo,
        SpreadsheetRepo $spreadsheetRepo,
        SpreadsheetService $service,
        BankRepo $bankRepo,
        CashFlowRepo $cashFlowRepo,
        NoteRepository $noteRepo
    )
    {
        $this->awardRepo = $awardRepo;
        $this->spreadsheetRepo = $spreadsheetRepo;
        $this->spreadsheetService = $service;
        $this->bankRepo = $bankRepo;
        $this->cashFlowRepo = $cashFlowRepo;
        $this->noteRepo = $noteRepo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = $this->bankRepo->getBanks();
        return view('awards.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AwardRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));

        $awardedStatusManual = $request->awarded_status_manual;
        $awardedStatusDeposit = $request->awarded_status_deposit;

        $data['awarded_status'] = $awardedStatusManual ? $awardedStatusManual : ($awardedStatusDeposit ? $awardedStatusDeposit : null);

        if (array_key_exists('awarded_upload_table', $data)) {
            $data['awarded_bank_id'] = 1;

            $file = Upload::file($request, 'awarded_upload_table', ['xlsx', 'xls']);
            $fullFileName = $file->getFullFileName();
            $fileName = $file->getFileName();

            $validationFile = $file->validation();
            $validationDocument = $this->spreadsheetService->isDocumentValid($fullFileName);

            if ($validationFile && $validationDocument) {
                $save = $this->awardRepo->save($data);

                $awardedValue = (float) $this->spreadsheetService->getAwardedValue();

                $data['awarded_value'] = $awardedValue;
                $data['awarded_upload_table'] = $fileName;

                $this->awardRepo->save($data, $save->id);

                $demandId = \Request::get('pedido_id');
                $this->spreadsheetRepo->saveShipment($fullFileName, $demandId, $save->id);
            }

            $messages = $this->spreadsheetService->getMessageErrors();
            if ($messages) {
                if (file_exists($fileName)) {
                    unlink($fileName);
                }
                return redirect()->back()
                    ->with('error', $messages);
            }

            if (!$validationDocument) {
                return redirect()->back()->withErrors('campos divergentes, verifique seu arquivo e tente novamente.');
            }
        }

        if ($request->awarded_type == 3 && $request->awarded_status_manual == 1) {
            $save = $this->awardRepo->save($data);
            $data = [
                'flow_movement_date' => $save->awarded_date_payment_manual,
                'flow_award_id' => $save->id,
                'flow_demand_id' => \Request::get('pedido_id'),
                'flow_bank_id' => $save->awarded_bank_id,
                'flow_hide_line' => 0
            ];

            $this->cashFlowRepo->save($data);
        }

        return redirect()->route('admin.show', [ 'id' => $request->awarded_demand_id, 'premiacao' => 1 ])
            ->with('message', 'Premiação cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $award = $this->spreadsheetRepo->find($id);
        $spreadsheets = $this->spreadsheetRepo->getSpreadsheetsWhereAwarded($id);
        return view('awards.show', compact('award', 'spreadsheets', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banks = $this->bankRepo->getBanks();
        $award = $this->awardRepo->find($id);
        $spreadsheetExists = $this->awardRepo->verifyIfSpreadsheetsExists($id);
        return view('awards.edit', compact('award', 'spreadsheetExists', 'banks', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update(AwardRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $awardedStatusManual = $request->awarded_status_manual;
        $awardedStatusDeposit = $request->awarded_status_deposit;

        $data['awarded_status'] = $awardedStatusManual ? $awardedStatusManual : ($awardedStatusDeposit ? $awardedStatusDeposit : null);

        $this->awardRepo->save($data, $id);

        $bankId = array_key_exists('awarded_bank_id', $request->all()) ? $request->awarded_bank_id : $this->awardRepo->getBankId($id)->bank_id;
        $cashFlowId = $this->cashFlowRepo->getCashFlowId($request->manual_deposit_id);

        if ($request->awarded_type == 3) {
            if ($request->awarded_status_manual == 1) {
                $cashData = [
                    'flow_movement_date' => date('Y-m-d'),
                    'flow_award_id' => $id,
                    'flow_demand_id' => \Request::get('pedido_id'),
                    'flow_bank_id' => $bankId,
                    'flow_hide_line' => 0,
                ];
            }

            if ($request->awarded_status_manual == 4) {
                $cashData = [
                    'flow_movement_date' => date('Y-m-d'),
                    'flow_award_id' => $id,
                    'flow_demand_id' => \Request::get('pedido_id'),
                    'flow_bank_id' => $bankId,
                    'flow_hide_line' => 1
                ];
            }

            if ($cashFlowId) {
                $this->cashFlowRepo->save($cashData, $cashFlowId);
            } else {
                $this->cashFlowRepo->save($cashData);
            }
        }
        return redirect()->route('admin.show', [ 'id' => \Request::get('pedido_id') ])
            ->with('message', 'Premiação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->awardRepo->removeAwardAndSpreadsheetAndAwardsInCashFlows($id);
        return redirect()->back()
            ->with('message', 'Premiação removida com sucesso!');
    }
}
