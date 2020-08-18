<?php

namespace App\Http\Controllers;

use App\Facades\AwardTypes\DepositAccount;
use App\Facades\UploadAward;
use App\Http\Requests\AwardRequest as Request;
use App\Repositories\{AwardRepository as AwardRepo,
    CashFlowRepository as CashFlowRepo,
    NoteRepository,
    SpreadsheetRepository as SpreadsheetRepo
};
use App\Services\SpreadsheetService;
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
    public function store(Request $request)
    {
        $demandId = $request->get('pedido_id');

        $data = $request->only(array_keys($request->rules()));
        $data['awarded_type'] = 2;
        $data['awarded_status'] = 3;
        $data['awarded_bank_id'] = 1;
        $data['awarded_demand_id'] = $demandId;

        $upload = new UploadAward($this->awardRepo, $this->spreadsheetService);
        $error = $upload->storeAward($request, $data, DepositAccount::class);

        if (is_array($error[0]) && $error[0]) {
            return redirect()->back()
                ->with('error', $error);
        }

        return redirect()->route('admin.show', [ 'id' => $demandId, 'premiacao' => 1 ])
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
    public function update(Request $request, $id)
    {
        $demandId = $request->get('pedido_id');

        $data = $request->only(array_keys($request->rules()));
        $data['awarded_type'] = 2;
        $data['awarded_bank_id'] = 1;
        $data['awarded_demand_id'] = $demandId;

        $this->awardRepo->save($data, $id);

        $cashFlowId = $this->cashFlowRepo->getCashFlowId($id);
        $cashData = [
            'flow_movement_date' => date('Y-m-d'),
            'flow_award_id' => $id,
            'flow_demand_id' => $demandId,
            'flow_bank_id' => $data['awarded_bank_id'],
        ];

        if ($request->awarded_type == 3) {
            if ($request->awarded_status_manual == 1) {
                $cashData['flow_hide_line'] = 0;
            }

            if ($cashFlowId) {
                $this->cashFlowRepo->save($cashData, $cashFlowId);
            } else {
                $this->cashFlowRepo->save($cashData);
            }
        }
        return redirect()->route('admin.show', [ 'id' => $demandId, 'premiacao' => 1 ])
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
