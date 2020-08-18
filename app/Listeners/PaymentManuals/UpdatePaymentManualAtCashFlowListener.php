<?php

namespace App\Listeners\PaymentManuals;

use App\Events\PaymentManuals\PaymentManualAtCashFlowCreated;

class UpdatePaymentManualAtCashFlowListener
{
    public function handle(PaymentManualAtCashFlowCreated $event)
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

            $cashFlow->save($data, $paramsPaymentManualPaydOut['flow_award_id']);
        }

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 4) {
            $paramsPaymentManualCancelled = [
                'flow_movement_date' => $data['awarded_date_payment_manual'],
                'flow_award_id' => $data['awarded_id'],
                'flow_demand_id' => $data['awarded_demand_id'],
                'flow_bank_id' => $data['awarded_bank_id'],
                'flow_hide_line' => 1
            ];

            $cashFlow->save($data, $paramsPaymentManualCancelled['flow_award_id']);
        }
    }
}
