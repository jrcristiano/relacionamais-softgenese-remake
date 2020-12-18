<?php

namespace App\Http\Controllers;

use App\Events\PaymentManuals\PaymentManualAtCashFlowCreated as SaveAtCashFlow;
use App\Repositories\AwardRepository as AwardRepo;
use App\Repositories\BankRepository as BankRepo;
use App\Http\Requests\PaymentManualRequest as Request;
use App\Repositories\CashFlowRepository as CashFlowRepo;

class PaymentManualController extends Controller
{
    private $awardRepo;
    private $bankRepo;
    private $cashFlowRepo;

    public function __construct(AwardRepo $awardRepo, BankRepo $bankRepo, CashFlowRepo $cashFlowRepo)
    {
        $this->awardRepo = $awardRepo;
        $this->bankRepo = $bankRepo;
        $this->cashFlowRepo = $cashFlowRepo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = $this->bankRepo->getBanks();
        return view('payment-manuals.create', compact('banks'));
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
        $data['awarded_demand_id'] = $request->get('pedido_id');
        $save = $this->awardRepo->save($data);

        $data['awarded_id'] = $save->id;
        event(new SaveAtCashFlow($this->cashFlowRepo, $data));

        return redirect()->route('admin.show', ['id' => $request->get('pedido_id'), 'premiacao' => 1])
            ->with('message', 'Premiação cadastrada com sucesso!');

    }

    public function show($id)
    {
        $paymentManual = $this->awardRepo->find($id);
        $bank = $this->bankRepo->find($paymentManual->awarded_bank_id);
        return view('payment-manuals.show', compact('paymentManual', 'bank'));
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
        $data['awarded_demand_id'] = $request->get('pedido_id');
        $this->awardRepo->save($data, $id);

        $data['awarded_id'] = $id;

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 1) {
            $paramsPaymentManualPaydOut = [
                'flow_movement_date' => $data['awarded_date_payment_manual'],
                'flow_award_id' => $data['awarded_id'],
                'flow_demand_id' => $data['awarded_demand_id'],
                'flow_bank_id' => $data['awarded_bank_id'],
                'flow_hide_line' => 0
            ];
            $this->cashFlowRepo->saveByParam($paramsPaymentManualPaydOut, 'flow_award_id', $paramsPaymentManualPaydOut['flow_award_id']);
        }

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 4) {
            $paramsPaymentManualCancelled = [
                'flow_movement_date' => $data['awarded_date_payment_manual'],
                'flow_award_id' => $data['awarded_id'],
                'flow_demand_id' => $data['awarded_demand_id'],
                'flow_bank_id' => $data['awarded_bank_id'],
                'flow_hide_line' => 1
            ];

            $this->cashFlowRepo->saveByParam($paramsPaymentManualCancelled, 'flow_award_id',  $paramsPaymentManualCancelled['flow_award_id']);
        }

        return redirect()->route('admin.show', ['id' => $request->get('pedido_id'), 'premiacao' => 1])
            ->with('message', 'Premiação editada com sucesso!');
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
