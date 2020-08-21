<?php

namespace App\Listeners\PaymentManuals;

use App\Events\PaymentManuals\PaymentManualAtCashFlowCreated as Event;

class PaymentManualAtCashFlowListener
{
    public function handle(Event $event)
    {
        $cashFlow = $event->getCashFlowRepo();
        $data = $event->getData();

        $params = [
            'flow_movement_date' => $data['awarded_date_payment_manual'],
            'flow_award_id' => $data['awarded_id'],
            'flow_demand_id' => $data['awarded_demand_id'],
            'flow_bank_id' => $data['awarded_bank_id'],
        ];

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 1) {
            $data['flow_hide_line'] = 0;
            $cashFlow->save($params);
        }

        if ($data['awarded_type'] == 3 && $data['awarded_status'] == 4) {
            $data['flow_hide_line'] = 1;
            $cashFlow->save($params);
        }
    }
}
