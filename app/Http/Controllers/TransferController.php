<?php

namespace App\Http\Controllers;

use App\CashFlow;
use App\Repositories\TransferRepository as TransferRepo;
use App\Repositories\BankRepository as BankRepo;
use App\Repositories\CashFlowRepository as CashFlowRepo;
use App\Http\Requests\TransferRequest as Request;

class TransferController extends Controller
{
    private $transferRepo;
    private $bankRepo;

    public function __construct(TransferRepo $transferRepo, BankRepo $bankRepo, CashFlowRepo $cashFlowRepo)
    {
        $this->transferRepo = $transferRepo;
        $this->bankRepo = $bankRepo;
        $this->cashFlowRepo = $cashFlowRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = $this->transferRepo->getTransfersByPaginate();
        return view('transfers.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = $this->bankRepo->getBanks();
        return view('transfers.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $transfer = $this->transferRepo->save($data);

        $data['id'] = $transfer->id;
        $params = [
            'flow_movement_date' => $data['transfer_date'],
            'flow_bank_id' => $data['transfer_account_credit'],
            'flow_transfer_id' => $data['id'],
        ];

        $params['flow_transfer_credit_or_debit'] = 1;
        $this->cashFlowRepo->save($params);

        $params['flow_transfer_credit_or_debit'] = 2;
        $this->cashFlowRepo->save($params);

        return redirect()->route('admin.financial.transfers');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transfer = $this->transferRepo->find($id);
        $banks = $this->bankRepo->getBanks();
        return view('transfers.edit', compact('transfer', 'banks'));
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
        $data = $request->only(array_keys($request->rules()));
        $transfer = $this->transferRepo->save($data, $id);

        $data['id'] = $transfer->id;
        $params = [
            'flow_movement_date' => $data['transfer_date'],
            'flow_bank_id' => $data['transfer_account_credit'],
            'flow_transfer_id' => $data['id'],
        ];

        $params['flow_transfer_credit_or_debit'] = 1;
        $this->cashFlowRepo->save($params, $id);

        $params['flow_transfer_credit_or_debit'] = 2;
        $this->cashFlowRepo->save($params, $id);

        return redirect()->route('admin.financial.transfers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->transferRepo->delete($id);
        return redirect()->route('admin.financial.transfers');
    }
}
