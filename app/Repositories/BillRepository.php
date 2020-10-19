<?php

namespace App\Repositories;

use App\Bill;
use App\NoteReceipt;
use Illuminate\Support\Facades\DB;

class BillRepository extends Repository
{
    protected $repository;
    private $noteReceipt;

    public function __construct(Bill $bill, NoteReceipt $noteReceipt)
    {
        $this->repository = $bill;
        $this->noteReceipt = $noteReceipt;
    }

    public function getDataBillsAndBanksByPaginate($pages = 100, array $filter = [])
    {
        $query = $this->repository->select([
            'bills.id as id',
            'provider_name',
            'bill_payday',
            'bill_due_date',
            'bill_value',
            'bill_payment_status',
            'bills.created_at as created_at',
            'bills.bill_description',
            'banks.bank_name',
            'banks.bank_account',
            'banks.bank_agency',

        ])
        ->leftJoin('banks', 'bills.bill_bank_id', '=', 'banks.id')
        ->join('providers', 'providers.id', '=', 'bills.bill_provider_id')
        ->orderBy('bills.id', 'desc');

        $between = array($filter['bill_in'], $filter['bill_until']);
        $status = $filter['bill_status'];
        $provider = $filter['bill_provider'];

        if ($between[0] && $between[1] && !$status && !$provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->paginate($pages);
        }

        if ($between[0] && $between[1] && $status && !$provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->where('bill_payment_status', '=', $status)
                ->paginate($pages);
        }

        if ($between[0] && $between[1] && $status && $provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->where('bill_payment_status', '=', $status)
                ->where('bill_provider_id', '=', $provider)
                ->paginate($pages);
        }

        if ($status && $provider) {
            return $query->where('bill_payment_status', $status)
                ->where('bill_provider_id', $provider)
                ->paginate($pages);
        }

        if ($provider) {
            return $query->where('bill_provider_id', $provider)
                ->paginate($pages);
        }

        if ($status) {
            return $query->where('bill_payment_status', $status)
                ->paginate($pages);
        }

        return $query->paginate($pages);
    }

    public function getBillTotal(array $filter)
    {
        $between = array($filter['bill_in'], $filter['bill_until']);
        $status = $filter['bill_status'];
        $provider = $filter['bill_provider'];

        if ($this->hasMinBillPayDay($between) == null && $this->hasMinBillDueDate($between) == null) {
            $between[0] = $this->repository->select(DB::raw('min(bill_payday) as minor_date'))
                ->first()->minor_date;
            $between[1] = $between[1];
        }

        $query = $this->repository->select(DB::raw('SUM(bills.bill_value) as bill_total'));

        if ($between[0] && $between[1] && !$status && !$provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->first()
                ->bill_total;
        }

        if ($between[0] && $between[1] && $status && !$provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->where('bill_payment_status', '=', $status)
                ->first()
                ->bill_total;
        }

        if ($between[0] && $between[1] && $status && $provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->where('bill_payment_status', '=', $status)
                ->where('bill_provider_id', '=', $provider)
                ->first()
                ->bill_total;
        }

        if ($between[0] && $between[1] && !$status && $provider) {
            return $query->whereBetween('bill_payday', $between)
                ->whereBetween('bill_due_date', $between)
                ->where('bill_provider_id', '=', $provider)
                ->first()
                ->bill_total;
        }

        if ($status && $provider) {
            return $query->where('bill_payment_status', $status)
                ->where('bill_provider_id', $provider)
                ->first()
                ->bill_total;
        }

        if ($provider) {
            return $query->where('bill_provider_id', $provider)
                ->first()
                ->bill_total;
        }

        if ($status) {
            return $query->where('bill_payment_status', $status)
                ->first()
                ->bill_total;
        }

        return $query->first()->bill_total;
    }

    public function hasMinBillPayDay(array $between)
    {
        $movementDate = $this->repository->select(DB::raw('min(bill_payday) as minor_date'))
            ->whereBetween('bill_payday', $between)
            ->first();

        return $movementDate->minor_date;
    }

    public function hasMinBillDueDate(array $between)
    {
        $movementDate = $this->repository->select(DB::raw('min(bill_due_date) as minor_date'))
            ->whereBetween('bill_due_date', $between)
            ->first();

        return $movementDate->minor_date;
    }

    public function getFirstBill($id)
    {
        return $this->repository->select([
            'bills.id as id',
            'provider_name',
            'bill_payday',
            'bill_due_date',
            'bill_value',
            'bills.created_at as created_at',
            'banks.bank_name',
            'banks.bank_agency',
            'banks.bank_account'
        ])
        ->join('banks', 'bills.bill_bank_id', '=', 'banks.id')
        ->join('providers', 'providers.id', '=', 'bills.bill_provider_id')
        ->orderBy('bills.id', 'desc')
        ->where('bills.id', $id)
        ->first();
    }

    public function getPatrimony($id)
    {
        $raw = DB::raw('sum(note_receipts.note_receipt_taxable_real_value) as patrimony');
        return (float) $this->noteReceipt->select($raw)
            ->where('note_receipt_id', $id)
            ->first()
            ->patrimony;
    }

    public function getAward($id)
    {
        $raw = DB::raw('sum(note_receipts.note_receipt_award_real_value) as award');
        return (float) $this->noteReceipt->select($raw)
            ->where('note_receipt_id', $id)
            ->first()
            ->award;
    }

    public function getOtherValues($id)
    {
        $raw = DB::raw('sum(note_receipts.note_receipt_other_value) as other_value');
        return (float) $this->noteReceipt->select($raw)
            ->where('note_receipt_id', $id)
            ->first()
            ->other_value;
    }
}
