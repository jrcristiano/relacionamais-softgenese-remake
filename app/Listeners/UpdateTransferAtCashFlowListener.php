<?php

namespace App\Listeners;

use App\Events\TransferAtCashFlowUpdated;

class UpdateTransferAtCashFlowListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransferAtCashFlowUpdated $event)
    {
        $cashFlow = $event->getCashFlowRepo();
        $data = $event->getData();

        $creditData = [
            'flow_bank_id' => $data['transfer_account_credit'],
            'flow_transfer_id' => $data['id'],
        ];

        $cashFlow->updateWhereFlowTransferCreditOrDebit($creditData, $data['id'], 1);

        $debitData = [
            'flow_bank_id' => $data['transfer_account_debit'],
            'flow_transfer_id' => $data['id'],
        ];

        $cashFlow->updateWhereFlowTransferCreditOrDebit($debitData, $data['id'], 2);
    }
}
