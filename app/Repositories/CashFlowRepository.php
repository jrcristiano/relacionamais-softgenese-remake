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
        return $this->repository->orderBy('id', 'desc')
            ->when($between !== [], function ($query) use ($between) {
                $query->whereBetween('flow_movement_date', $between);
            })
            ->when($bankId !== null, function ($query) use ($bankId) {
                $query->where('flow_bank_id', '=', $bankId);
            })
            ->visible()
            ->get();
    }

    public function getBillTotal(array $between = [], $bankId = null)
    {
        return $this->repository->select(DB::raw('sum(bills.bill_value) as value_total'))
            ->join('bills', 'cash_flows.flow_bill_id', 'bills.id')
            ->when($between !== [], function ($query) use ($between) {
                $query->whereBetween('flow_movement_date', $between);
            })
            ->when($bankId !== null, function ($query) use ($bankId) {
                $query->where('flow_bank_id', '=', $bankId);
            })
            ->first();
    }

    public function getReceivePatrimonyTotal(array $between = [], $bankId = null)
    {
        return $this->repository->select(DB::raw('sum(note_receipts.note_receipt_taxable_real_value) as value'))
            ->join('note_receipts', 'cash_flows.flow_receive_id', 'note_receipts.id')
            ->when($between !== [], function ($query) use ($between) {
                $query->whereBetween('flow_movement_date', $between);
            })
            ->when($bankId !== null, function ($query) use ($bankId) {
                $query->where('flow_bank_id', '=', $bankId);
            })
            ->first();
    }

    public function getReceiveOtherValueTotal(array $between = [], $bankId = null)
    {
        return $this->repository->select(DB::raw('sum(note_receipts.note_receipt_other_value) as value'))
            ->join('note_receipts', 'cash_flows.flow_receive_id', 'note_receipts.id')
            ->when($between !== [], function ($query) use ($between) {
                $query->whereBetween('flow_movement_date', $between);
            })
            ->when($bankId !== null, function ($query) use ($bankId) {
                $query->where('flow_bank_id', '=', $bankId);
            })
            ->first();
    }

    public function getCreditTransferValueTotal(array $between = [], $bankId = null)
    {
        return $this->repository->select(DB::raw('sum(transfers.transfer_value) as value'))
            ->join('transfers', 'cash_flows.flow_transfer_id', 'transfers.id')
            ->when($between !== [], function ($query) use ($between) {
                $query->whereBetween('flow_movement_date', $between);
            })
            ->when($bankId !== null, function ($query) use ($bankId) {
                $query->where('flow_bank_id', '=', $bankId);
            })
            ->where('flow_transfer_credit_or_debit', 1)
            ->first();
    }

    public function getDebitTransferValueTotal(array $between = [], $bankId = null)
    {
        return $this->repository->select(DB::raw('sum(transfers.transfer_value) as value'))
            ->join('transfers', 'cash_flows.flow_transfer_id', 'transfers.id')
            ->when($between !== [], function ($query) use ($between) {
                $query->whereBetween('flow_movement_date', $between);
            })
            ->when($bankId !== null, function ($query) use ($bankId) {
                $query->where('flow_bank_id', '=', $bankId);
            })
            ->where('flow_transfer_credit_or_debit', 2)
            ->first();
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->update($data);
    }

    public function removeBillsWhere($id)
    {
        return $this->repository->where('flow_bill_id', $id)
            ->delete();
    }

    public function saveCreditTransferByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->where('flow_transfer_credit_or_debit', 1)
            ->update($data);
    }

    public function saveDebitTransferByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->where('flow_transfer_credit_or_debit', 2)
            ->update($data);
    }

    public function saveWhereBillId(array $data, $id)
    {
        return $this->repository->where('flow_bill_id', $id)
            ->update($data);
    }

    public function removeWhereBillId($id)
    {
        return $this->repository->where('flow_bill_id', $id)
            ->delete();
    }
}
