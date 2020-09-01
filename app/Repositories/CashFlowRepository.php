<?php

namespace App\Repositories;

use App\CashFlow;
use Illuminate\Support\Facades\DB;

class CashFlowRepository extends Repository
{
    protected $repository;

    public function __construct(CashFlow $cashFlow)
    {
        $this->repository = $cashFlow;
    }

    public function getCashFlowsByPaginate($perPage = 200, array $between = [], $bankId = null)
    {
        $query = $this->repository->select([
            'demands.id as demand_id',
            'awards.id as award_id',
            'cash_flows.flow_movement_date',
            'cash_flows.flow_receive_id',
            'cash_flows.flow_demand_id',
            'cash_flows.flow_bill_id',
            'cash_flows.flow_award_id',
            'cash_flows.flow_transfer_credit_or_debit',
            'clients.client_company',
            'clients.client_cnpj',
            'banks.bank_name',
            'banks.bank_account',
            'banks.bank_agency',
            'note_receipts.note_receipt_award_real_value as award_value',
            'note_receipts.note_receipt_taxable_real_value as patrimony',
            'bills.id as bill_id',
            'bills.bill_value',
            'providers.provider_name',
            'awards.awarded_value',
            'awards.awarded_demand_id as awarded_demand_id',
            'notes.note_number',
            'notes.id as note_id',
            'transfers.id as transfer_id',
            'transfers.transfer_value',
            'transfers.transfer_type',
            'transfers.created_at'
        ])
        ->addSelect(DB::raw('SUM(spreadsheets.spreadsheet_value) as shipment_value'))
        ->addSelect(DB::raw('SUM(note_receipts.note_receipt_other_value) as other_value'))
        ->leftJoin('transfers', 'cash_flows.flow_transfer_id', '=', 'transfers.id')
        ->leftJoin('demands', 'cash_flows.flow_demand_id', '=', 'demands.id')
        ->leftJoin('notes', 'cash_flows.flow_demand_id', '=', 'notes.note_demand_id')
        ->leftJoin('clients', 'demands.demand_client_cnpj', '=', 'clients.client_cnpj')
        ->leftJoin('note_receipts', 'cash_flows.flow_receive_id', '=', 'note_receipts.id')
        ->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
        ->leftJoin('bills', 'cash_flows.flow_bill_id', '=', 'bills.id')
        ->leftJoin('spreadsheets', 'cash_flows.flow_award_id', '=', 'spreadsheets.spreadsheet_award_id')
        ->leftJoin('awards', 'cash_flows.flow_award_id', '=', 'awards.id')
        ->leftJoin('providers', 'bills.bill_provider_id', '=', 'providers.id')
        ->whereNull('awarded_shipment_cancelled')
        ->distinct(['note_receipts.id'])
        ->groupBy('cash_flows.id')
        ->orderBy('cash_flows.flow_movement_date', 'asc')
        ->orderBy('cash_flows.id', 'asc')
        ->where('cash_flows.flow_hide_line', 0)
        ->whereNull('spreadsheets.spreadsheet_chargeback')
        ->whereNull('demands.deleted_at');

        if (in_array(null, $between) && $bankId == null) {
            return $query->paginate($perPage);
        }

        if (!in_array(null, $between) && $bankId == null) {
            return $query->whereBetween('flow_movement_date', $between)
                ->paginate($perPage);
        }

        if (!in_array(null, $between) && isset($bankId)) {
            return $query->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId)
                ->paginate($perPage);
        }

        if (in_array(null, $between) && isset($bankId)) {
            return $query->where('banks.id', '=', $bankId)
                ->paginate($perPage);
        }

        return $query->whereNull('awarded_shipment_cancelled')
            ->whereBetween('flow_movement_date', $between)
            ->where('banks.id', '=', $bankId)
            ->paginate($perPage);
    }

    public function getPatrimonyTotal(array $between = [], $bankId = null)
    {
        if ($this->hasMovementDateBetween($between) == null) {
            $between[0] = '2019-12-08';
        }

        $transferQuery = $this->repository->select([
                'cash_flows.flow_transfer_credit_or_debit as credit_or_debit',
                'transfers.transfer_value',
            ])
            ->leftJoin('transfers', 'cash_flows.flow_transfer_id', '=', 'transfers.id')
            ->where('transfers.transfer_type', 1);

        $rawReceiveQuery = DB::raw('sum(note_receipt_taxable_real_value) as patrimony_total');
        $receiveQuery = $this->repository->select($rawReceiveQuery)
            ->leftJoin('note_receipts', 'cash_flows.flow_receive_id', '=', 'note_receipts.id')
            ->addSelect(DB::raw('SUM(note_receipts.note_receipt_other_value) as other_value'));

        $billQuery = $this->repository->select('bill_value')
            ->groupBy('bills.id')
            ->join('bills', 'cash_flows.flow_bill_id', '=', 'bills.id')
            ->whereBetween('flow_movement_date', $between);

        if (in_array(null, $between) && $bankId == null) {
            $billValue = 0;
            foreach ($billQuery->get() as $key => $bill) {
                $billValue += $bill->bill_value;
            }

            $patrimony = (float) $receiveQuery->first()->patrimony_total ?? 0;
            return $patrimony - $billValue;
        }

        if (!in_array(null, $between) && $bankId == null) {
            $billQuery->whereBetween('flow_movement_date', $between);

            $patrimony = $receiveQuery->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between);

            $billValue = 0;
            foreach ($billQuery->get() as $key => $bill) {
                $billValue += $bill->bill_value;
            }

            $transferQuery = $transferQuery->whereBetween('flow_movement_date', $between);

            $transferCredit = 0;
            $transferDebit = 0;
            foreach ($transferQuery->get() as $key => $obj) {
                if ($obj->credit_or_debit == 1) {
                    $transferCredit += $obj->transfer_value;
                }

                if ($obj->credit_or_debit == 2) {
                    $transferDebit += $obj->transfer_value;
                }
            }

            $otherValue = $patrimony->first()->other_value ?? 0;
            $patrimony = (float) $patrimony->first()->patrimony_total ?? 0;
            $patrimony = $patrimony + $otherValue;

            return ($patrimony - $billValue) + ($transferCredit - $transferDebit);
        }

        if (!in_array(null, $between) && isset($bankId)) {
            $billQuery->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId);

            $patrimony = $receiveQuery->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId);

            $billValue = 0;
            foreach ($billQuery->get() as $key => $bill) {
                $billValue += $bill->bill_value;
            }

            $transferQuery = $transferQuery->whereBetween('flow_movement_date', $between)
                ->where('flow_bank_id', $bankId);

            $transferCredit = 0;
            $transferDebit = 0;
            foreach ($transferQuery->get() as $key => $obj) {
                if ($obj->credit_or_debit == 1) {
                    $transferCredit += $obj->transfer_value;
                }

                if ($obj->credit_or_debit == 2) {
                    $transferDebit += $obj->transfer_value;
                }
            }

            $otherValue = $patrimony->first()->other_value ?? 0;
            $patrimony = (float) $patrimony->first()->patrimony_total ?? 0;
            $patrimony = $patrimony + $otherValue;

            return ($patrimony - $billValue) + ($transferCredit - $transferDebit);
        }

        if (in_array(null, $between) && isset($bankId)) {
            $patrimony = $receiveQuery->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->where('banks.id', '=', $bankId);

            $billValue = 0;
            foreach ($billQuery->get() as $key => $bill) {
                $billValue += $bill->bill_value;
            }

            $transferQuery = $transferQuery->where('flow_bank_id', $bankId);

            $transferCredit = 0;
            $transferDebit = 0;
            foreach ($transferQuery->get() as $key => $obj) {
                if ($obj->credit_or_debit == 1) {
                    $transferCredit += $obj->transfer_value;
                }

                if ($obj->credit_or_debit == 2) {
                    $transferDebit += $obj->transfer_value;
                }
            }

            $otherValue = $patrimony->first()->other_value ?? 0;
            $patrimony = (float) $patrimony->first()->patrimony_total ?? 0;
            $patrimony = $patrimony + $otherValue;

            return ($patrimony - $billValue) + ($transferCredit - $transferDebit);
        }
    }

    public function getAwardTotal(array $between = [], $bankId = null)
    {
        if ($this->hasMovementDateBetween($between) == null) {
            $between[0] = '2019-12-08';
        }

        $transferQuery = $this->repository->select([
            'cash_flows.flow_transfer_credit_or_debit as credit_or_debit',
            'transfers.transfer_value',
        ])
        ->leftJoin('transfers', 'cash_flows.flow_transfer_id', '=', 'transfers.id')
        ->where('transfers.transfer_type', 2);

        $queryReceiveRaw = DB::raw('sum(note_receipt_award_real_value) as award_real_value');
        $queryReceive = $this->repository->select($queryReceiveRaw)
            ->join('note_receipts', 'cash_flows.flow_receive_id', '=', 'note_receipts.id');

        $queryShipmentRaw = DB::raw('sum(spreadsheets.spreadsheet_value) as shipment_value');
        $queryShipment = $this->repository->select($queryShipmentRaw)
            ->leftJoin('awards', 'cash_flows.flow_award_id', '=', 'awards.id')
            ->leftJoin('spreadsheets', 'cash_flows.flow_award_id', '=', 'spreadsheets.spreadsheet_award_id')
            ->whereNull('awarded_shipment_cancelled')
            ->whereNull('spreadsheet_chargeback');

        $queryAwardManualRaw = DB::raw('sum(awards.awarded_value) as award_manual_value');
        $awardManuals = $this->repository->select($queryAwardManualRaw)
            ->leftJoin('awards', 'cash_flows.flow_award_id', '=', 'awards.id')
            ->where('awards.awarded_type', 3)
            ->where('awards.awarded_status', 1);

        $queryAwardAcessoCardRaw =  DB::raw('sum(awards.awarded_value) as acesso_card_value');
        $awardAcessoCards = $this->repository->select($queryAwardAcessoCardRaw)
            ->leftJoin('awards', 'cash_flows.flow_award_id', '=', 'awards.id')
            ->where('awards.awarded_type', 1)
            ->where('awards.awarded_status', 1);

        if (in_array(null, $between) && $bankId == null) {
            $awards = $queryReceive->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id');

            $shipments = $queryShipment->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereNull('awarded_shipment_cancelled');

            $awards = (float) $queryReceive->first()->award_real_value ?? 0;
            $shipments = (float) $shipments->first()->shipment_value ?? 0;
            $awardManualsValue = (float) $awardManuals->first()->award_manual_value ?? 0;
            $awardAcessoCards = (float) $awardAcessoCards->first()->acesso_card_value ?? 0;

            return ($awards - $shipments) - $awardManualsValue - $awardAcessoCards;
        }

        if (!in_array(null, $between) && $bankId == null) {
            $transferTotal = $this->getTransferTotal($between, $bankId);

            $awards = $queryReceive->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between);

            $shipments = $queryShipment->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between);

            $awardManualsValue = (float) $awardManuals->whereBetween('flow_movement_date', $between)
                ->first()->award_manual_value ?? 0;

            $awards = (float) $queryReceive->first()->award_real_value ?? 0;
            $shipments = (float) $queryShipment->first()->shipment_value ?? 0;
            $awardAcessoCards = (float) $awardAcessoCards->first()->acesso_card_value ?? 0;

            return ($awards - $shipments + $transferTotal) - $awardManualsValue - $awardAcessoCards;
        }

        if (in_array(null, $between) && isset($bankId)) {
            $transferTotal = $this->getTransferTotal($between, $bankId);

            $awards = $queryReceive->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->where('banks.id', '=', $bankId);

            $shipments = $queryShipment->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->where('banks.id', '=', $bankId)
                ->whereNull('awarded_shipment_cancelled');

            $transferQuery = $transferQuery->where('flow_bank_id', $bankId);

            $transferCredit = 0;
            $transferDebit = 0;
            foreach ($transferQuery->get() as $key => $obj) {
                if ($obj->credit_or_debit == 1) {
                    $transferCredit += $obj->transfer_value;
                }

                if ($obj->credit_or_debit == 2) {
                    $transferDebit += $obj->transfer_value;
                }
            }

            $awards = (float) $awards->first()->award_real_value ?? 0;
            $shipments = (float) $shipments->first()->shipment_value ?? 0;

            $awardManualsValue = (float) $awardManuals->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->where('banks.id', '=', $bankId)
                ->first()->award_manual_value ?? 0;

            $awardAcessoCards = (float) $awardAcessoCards->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->where('banks.id', '=', $bankId)
                ->first()->acesso_card_value ?? 0;

            return ($awards - $shipments + $transferTotal) - $awardManualsValue - $awardAcessoCards + ($transferCredit - $transferDebit);
        }

        if (!in_array(null, $between) && isset($bankId)) {
            $transferTotal = $this->getTransferTotal($between, $bankId);

            $awards = $queryReceive->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId);

            $shipments = $queryShipment->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId)
                ->whereNull('awarded_shipment_cancelled');

            $awards = (float) $awards->first()->award_real_value ?? 0;
            $shipments = (float) $shipments->first()->shipment_value ?? 0;
            $awardManualsValue = (float) $awardManuals->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId)
                ->first()->award_manual_value ?? 0;

            $awardAcessoCards = (float) $awardAcessoCards->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
                ->whereBetween('flow_movement_date', $between)
                ->where('banks.id', '=', $bankId)
                ->first()->acesso_card_value ?? 0;

            return ($awards - $shipments + $transferTotal) - $awardManualsValue - $awardAcessoCards;
        }

        $awards = $queryReceive->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
            ->whereBetween('flow_movement_date', $between)
            ->where('banks.id', '=', $bankId);

        $shipments = $queryShipment->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
            ->whereBetween('flow_movement_date', $between)
            ->where('banks.id', '=', $bankId)
            ->whereNull('awarded_shipment_cancelled');

        $awards = (float) $awards->first()->award_real_value ?? 0;
        $shipments = (float) $shipments->first()->shipment_value ?? 0;
        $awardManualsValue = (float) $awardManuals->leftJoin('banks', 'cash_flows.flow_bank_id', '=', 'banks.id')
            ->whereBetween('flow_movement_date', $between)
            ->whereBetween('flow_movement_date', $between)
            ->where('banks.id', '=', $bankId)
            ->first()->award_manual_value ?? 0;

        return ($awards - $shipments) - $awardManualsValue - $awardAcessoCards;
    }

    public function hasMovementDateBetween($between)
    {
        $movementDate = $this->repository->whereBetween('flow_movement_date', $between)
            ->first();

        return $movementDate->between_date ?? null;
    }

    public function updateWhereFlowTransferCreditOrDebit($data, $id, $creditOrDebit)
    {
        return $this->repository->where('flow_transfer_credit_or_debit', $creditOrDebit)
            ->where('flow_transfer_id', $id)
            ->update($data);
    }

    public function getCashFlowId($id)
    {
        return $this->repository->select('id')
                ->where('flow_award_id', $id)
                ->first()
                ->id ?? null;
    }

    public function updateWhereFlowReceiveId($data, $id)
    {
        return $this->repository->where('flow_receive_id', $id)
            ->update($data);
    }

    public function removeWhereFlowReceiveId($id)
    {
        return $this->repository->where('flow_receive_id', $id)
            ->delete();
    }

    public function getTransferTotal(array $between, $bankId)
    {
        $queryTranfersCredit = $this->repository->select('transfer_value')
            ->leftJoin('transfers', 'flow_transfer_id', '=', 'transfers.id')
            ->whereBetween('flow_movement_date', $between)
            ->where('flow_transfer_credit_or_debit', 1)
            ->where('flow_bank_id', $bankId)
            ->where('transfer_type', 2)
            ->get();

        $valueCredit = 0;
        foreach ($queryTranfersCredit as $key => $value) {
            $valueCredit += $value->transfer_value;
        }

        $queryTranfersDebit = $this->repository->select('transfer_value')
            ->leftJoin('transfers', 'flow_transfer_id', '=', 'transfers.id')
            ->whereBetween('flow_movement_date', $between)
            ->where('flow_transfer_credit_or_debit', 2)
            ->where('flow_bank_id', $bankId)
            ->where('transfer_type', 2)
            ->get();

        $valueDebit = 0;
        foreach ($queryTranfersDebit as $key => $value) {
            $valueDebit -= $value->transfer_value;
        }

        $transferTotal = $valueDebit + $valueCredit;
        return $transferTotal;
    }

    public function removeBillsWhere($id)
    {
        return $this->repository->where('flow_bill_id', $id)
            ->delete();
    }
}
