<?php

namespace App\Http\Controllers;

use App\CashFlow;
use App\Events\Transfers\TransferAtCashFlowSaved;
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

        $cashFlowRepo = new CashFlowRepo(new CashFlow);

        event(new TransferAtCashFlowSaved($cashFlowRepo, $data));
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
        $this->transferRepo->save($data, $id);

        $data['id'] = $id;
        $cashFlowRepo = new CashFlowRepo(new CashFlow);

        event(new TransferAtCashFlowSaved($cashFlowRepo, $data));
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
