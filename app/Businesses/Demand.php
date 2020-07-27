<?php

namespace App\Businesses;

class Demand
{
    public function getNoteFiscalCalculationTotal(array $data): array
    {
        $prizeAmount = $data['demand_prize_amount'] ?? 0;
        $taxAmount = $data['demand_taxable_amount'] ?? 0;
        $taxManual = $data['demand_taxable_manual'] ?? 0;
        $otherValue = $data['demand_other_value'] ?? 0;

        $noteFiscalCalc = $prizeAmount + $taxAmount + $taxManual + $otherValue;
        $data['demand_nfe_total'] = $noteFiscalCalc;

        return $data;
    }

    public function getTaxableAmount(array $data): float
    {
        $rateAdmin = $this->getDemandRateAdmin($data);
        $rateAdmin = (float) $rateAdmin[0]->client_rate_admin;

        $prizeAmount = (float) $data['demand_prize_amount'];

        $taxableAmount = $prizeAmount * $rateAdmin;
        $taxableAmount = $taxableAmount;

        return $taxableAmount;
    }

    private function getDemandRateAdmin(array $data)
    {
        return \App\Client::select(['client_rate_admin'])
            ->where('client_cnpj', $data['demand_client_cnpj'])
            ->get();
    }
}
