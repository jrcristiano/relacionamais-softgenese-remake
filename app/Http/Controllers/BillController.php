<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Repositories\BankRepository as BankRepo;
use App\Repositories\BillRepository as BillRepo;
use App\Repositories\CashFlowRepository as CashFlowRepo;
use App\Repositories\ProviderRepository as ProviderRepo;
use Illuminate\Http\Request;

class BillController extends Controller
{
    protected $billRepo;
    protected $cashFlowRepo;
    protected $providerRepo;
    protected $bankRepo;

    public function __construct(BillRepo $billRepo, CashFlowRepo $cashFlowRepo, ProviderRepo $providerRepo, BankRepo $bankRepo)
    {
        $this->billRepo = $billRepo;
        $this->cashFlowRepo = $cashFlowRepo;
        $this->providerRepo = $providerRepo;
        $this->bankRepo = $bankRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'bill_in' => $request->bill_in,
            'bill_until' => $request->bill_until,
            'bill_status' => $request->bill_status,
            'bill_provider' => $request->bill_provider
        ];

        $providers = $this->providerRepo->getProviderNameAndId();
        $bills = $this->billRepo->getDataBillsAndBanksByPaginate(50, $filter);
        $billTotal = $this->billRepo->getBillTotal($filter);

        return view('bills.index', compact('bills', 'billTotal', 'providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = $this->providerRepo->getProviderNameAndId();
        $banks = $this->bankRepo->getBanks();
        return view('bills.create', compact('providers', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $bill = $this->billRepo->save($data);

        if ($data['bill_payment_status'] == 1) {
            $flowData['flow_movement_date'] = $data['bill_payday'];
            $flowData['flow_bank_id'] = $data['bill_bank_id'];
            $flowData['flow_bill_id'] = $bill->id;

            $this->cashFlowRepo->save($flowData);
        }

        return redirect()->route('admin.financial.bills', [
            'bill_in' => date('Y-m-d'),
            'bill_until' => date('Y-m-d')
        ])
        ->with('message', 'Conta cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = $this->billRepo->getFirstBill($id);
        return view('bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $providers = $this->providerRepo->getProviderNameAndId();
        $banks = $this->bankRepo->getBanks();
        $bill = $this->billRepo->find($id);

        return view('bills.edit', compact('providers', 'banks', 'bill', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BillRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $this->billRepo->save($data, $id);

        if ($data['bill_payment_status'] == 1) {
            $flowData['flow_movement_date'] = $data['bill_payday'];
            $flowData['flow_bank_id'] = $data['bill_bank_id'];
            $flowData['flow_bill_id'] = $id;

            $this->cashFlowRepo->saveWhereBillId($flowData, $flowData['flow_bill_id']);
        }

        if ($data['bill_payment_status'] == 2) {
            $this->cashFlowRepo->removeWhereBillId($id);
        }

        return redirect()->route('admin.financial.bills')
            ->with('message', 'Conta editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->billRepo->delete($id);
        $this->cashFlowRepo->removeBillsWhere($id);
        return redirect()->route('admin.financial.bills');
    }
}
