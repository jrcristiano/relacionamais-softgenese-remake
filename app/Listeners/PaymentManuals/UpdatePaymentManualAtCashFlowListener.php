<?php

namespace App\Listeners\PaymentManuals;

use App\Events\PaymentManuals\PaymentManualAtCashFlowCreated as Event;

class UpdatePaymentManualAtCashFlowListener
{
    public function handle(Event $event)
    {
        $cashFlow = $event->getCashFlowRepo();
        $data = $event->getData();

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 1) {
            $paramsPaymentManualPaydOut = [
                'flow_movement_date' => $data['awarded_date_payment_manual'],
                'flow_award_id' => $data['awarded_id'],
                'flow_demand_id' => $data['awarded_demand_id'],
                'flow_bank_id' => $data['awarded_bank_id'],
                'flow_hide_line' => 0
            ];

            $cashFlow->saveByParam($data, 'flow_award_id', $paramsPaymentManualPaydOut['flow_award_id']);
        }

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 4) {
            $paramsPaymentManualCancelled = [
                'flow_movement_date' => $data['awarded_date_payment_manual'],
                'flow_award_id' => $data['awarded_id'],
                'flow_demand_id' => $data['awarded_demand_id'],
                'flow_bank_id' => $data['awarded_bank_id'],
                'flow_hide_line' => 1
            ];

            dd($paramsPaymentManualPaydOut['flow_award_id']);

            $cashFlow->saveByParam($data, 'flow_award_id',  $paramsPaymentManualCancelled['flow_award_id']);
        }
    }
}
