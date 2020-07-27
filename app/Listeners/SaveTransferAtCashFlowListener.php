<?php

namespace App\Listeners;

use App\Events\TransferAtCashFlowCreated;

class SaveTransferAtCashFlowListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransferAtCashFlowCreated $event)
    {
        $cashFlowRepo = $event->getCashFlowRepo();
        $data = $event->getData();

        $creditData = [
            'flow_movement_date' => $data['transfer_date'],
            'flow_bank_id' => $data['transfer_account_credit'],
            'flow_transfer_id' => $data['id'],
            'flow_transfer_credit_or_debit' => 1,
        ];

        $cashFlowRepo->save($creditData);

        $debitData = [
            'flow_movement_date' => $data['transfer_date'],
            'flow_bank_id' => $data['transfer_account_debit'],
            'flow_transfer_id' =>  $data['id'],
            'flow_transfer_credit_or_debit' => 2,
        ];

        $cashFlowRepo->save($debitData);
    }
}
