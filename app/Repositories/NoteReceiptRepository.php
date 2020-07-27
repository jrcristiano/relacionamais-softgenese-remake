<?php

namespace App\Repositories;

use App\NoteReceipt;

class NoteReceiptRepository extends Repository
{
    protected $repository;

    public function __construct(NoteReceipt $repository)
    {
        $this->repository = $repository;
    }

    public function getNoteReceiptsByPaginate($perPage = 50, $id = null)
    {
        return $this->repository->select([
            'note_receipts.id',
            'note_receipts.note_receipt_award_real_value',
            'note_receipts.note_receipt_taxable_real_value',
            'note_receipts.note_receipt_date',
            'note_receipts.note_receipt_id',
            'note_receipts.note_receipt_other_value',

            'banks.bank_name',
            'banks.bank_account',
            'banks.bank_agency',
        ])
        ->join('banks', 'note_receipts.note_receipt_account_id', '=', 'banks.id')
        ->orderBy('note_receipts.id', 'desc')
        ->where('note_receipt_id', $id)
        ->paginate($perPage);
    }

    public function hasNoteReceipts($id)
    {
        $this->repository->where('note_receipt_id', $id)->first();
    }
}
