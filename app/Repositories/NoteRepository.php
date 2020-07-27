<?php

namespace App\Repositories;

use App\Bank;
use App\Note;

class NoteRepository extends Repository
{
    protected $repository;

    public function __construct(Note $note, Bank $bank)
    {
        $this->repository = $note;
        $this->bankRepo = $bank;
    }

    public function findNotesWhereNoteDemandId($requestDemandId)
    {
        return $this->repository->select([
            'notes.*',

            'demands.id as demand_id',
            'demands.demand_client_cnpj',
            'demands.demand_client_name',
            'demands.demand_prize_amount as prize_amount',
            'demands.demand_taxable_amount as taxable_amount',
        ])
        ->join('demands', 'notes.note_demand_id', '=', 'demands.id')
        ->where('notes.note_demand_id', '=', $requestDemandId)
        ->first();
    }

    public function getNoteNumberWhereDemandId($demandId)
    {
        return $this->repository->select(['note_number'])
            ->where('note_demand_id', $demandId)
            ->first();
    }

    public function getStatus($id)
    {
        return $this->repository->select('notes.note_status')
            ->where('id', $id)
            ->first()
            ->note_status;
    }

    public function getBanks()
    {
        return $this->bankRepo->select([
            'banks.id',
            'banks.bank_name',
            'banks.bank_account',
            'banks.bank_agency'
        ])
        ->orderBy('banks.id', 'desc')
        ->get();
    }
}
