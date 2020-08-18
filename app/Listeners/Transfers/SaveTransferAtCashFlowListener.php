<?php

namespace App\Listeners\Transfers;

use App\Events\Transfers\TransferAtCashFlowSaved;

class SaveTransferAtCashFlowListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransferAtCashFlowSaved $event)
    {
        $cashFlowRepo = $event->getCashFlowRepo();
        $data = $event->getData();

        $params = [
            'flow_movement_date' => $data['transfer_date'],
            'flow_bank_id' => $data['transfer_account_credit'],
            'flow_transfer_id' => $data['id'],
        ];

        $params['flow_transfer_credit_or_debit'] = 1;
        $cashFlowRepo->save($params);

        $params['flow_transfer_credit_or_debit'] = 2;
        $cashFlowRepo->save($params);
    }
}
