<?php

namespace App\Repositories;

use App\Demand;
use App\Businesses\Demand as DemandBusiness;
use Illuminate\Support\Facades\DB;

class DemandRepository extends Repository
{
    protected $repository;

    public function __construct(Demand $demand)
    {
        $this->repository = $demand;
    }

    public function getDemandsByPaginate($perPage)
    {
        return $this->repository->select([
            'demands.*',
            'demands.demand_prize_amount as prize_amount',
            'notes.note_number',
            'awards.awarded_demand_id',
        ])
        ->leftJoin('awards', 'demands.id', '=', 'awards.awarded_demand_id')
        ->leftJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
        ->leftJoin('note_receipts', 'demands.id', '=', 'note_receipts.note_receipt_demand_id')
        ->orderBy('demands.id', 'desc')
        ->groupBy('demands.id')
        ->paginate($perPage);
    }

    public function getSale()
    {
        return $this->repository->select([
            'demands.demand_prize_amount as prize_amount',
        ])
        ->addSelect(DB::raw('sum(note_receipts.note_receipt_award_real_value) as sale'))
        ->leftJoin('note_receipts', 'demands.id', '=', 'note_receipts.note_receipt_demand_id')
        ->first();
    }

    public function showFirstDemand($id)
    {
        return $this->repository->select([
            'demands.*',
            'notes.note_number',
            'notes.id as note_id',
            'awards.awarded_demand_id',
        ])
        ->leftJoin('awards', 'demands.id', '=', 'awards.awarded_demand_id')
        ->where('demands.id', $id)
        ->leftJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
        ->orderBy('demands.id', 'desc')
        ->first();
    }

    public function getDataDemandsNotesBanksByPaginate($perPage, $id = null)
    {
        return $this->repository->select([
            'demands.demand_prize_amount',
            'demands.demand_taxable_amount',

            'notes.id',
            'notes.note_number',
            'notes.note_status',
            'notes.note_due_date',
            'notes.note_receipt_date',
            'notes.created_at',

            'banks.bank_account',
            'banks.bank_name',
            'banks.bank_agency',
        ])
        ->selectRaw('count(note_receipts.note_receipt_id) as receipts')
        ->where('notes.note_demand_id', '=', $id)
        ->rightJoin('notes', 'demands.id', '=', 'notes.note_demand_id')
        ->leftJoin('banks', 'notes.note_account_receipt_id', '=', 'banks.id')
        ->leftJoin('note_receipts', 'notes.id', '=', 'note_receipts.note_receipt_id')
        ->orderBy('notes.id', 'desc')
        ->paginate($perPage);
    }

    public function getAwardsInDemandsByPaginate($id, $perPage = 100)
    {
        return $this->repository->find($id)
            ->awards()
            ->orderBy('awards.id', 'desc')
            ->paginate($perPage);
    }

    public function getSumSpreasheetValue($id)
    {
        return \App\Spreadsheet::select(DB::raw('SUM(spreadsheets.spreadsheet_value) as value'))
            ->where('spreadsheet_demand_id', $id)
            ->whereNull('spreadsheet_chargeback')
            ->first();
    }

    public function find($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function saveDemand($data, $id = null)
    {
        $business = new DemandBusiness;

        if (array_key_exists('demand_taxable_manual', $data) && !$id) {
            $data['demand_other_value'] = $data['demand_other_value'] ?? 0;
            $data['demand_taxable_manual'] = toReal($data['demand_taxable_manual'])  ?? 0;
            $data['demand_prize_amount'] = $data['demand_prize_amount'];

            $data = $business->getNoteFiscalCalculationTotal($data);
            $data['demand_taxable_manual'] = $data['demand_taxable_manual'];

            return $this->repository->create($data);
        }

        if (!array_key_exists('demand_taxable_manual', $data) && !$id) {
            $data['demand_other_value'] = $data['demand_other_value'] ?? 0;
            $data['demand_prize_amount'] = $data['demand_prize_amount'] ?? 0;
            $data['demand_taxable_manual'] = 0;

            $data['demand_taxable_amount'] = $business->getTaxableAmount($data) / 100;
            $data = $business->getNoteFiscalCalculationTotal($data);

            return $this->repository->create($data);
        }

        if (array_key_exists('demand_taxable_manual', $data) && $id) {
            $taxManualFormatted = toReal($data['demand_taxable_manual']);

            $data['demand_taxable_manual'] = $taxManualFormatted;
            $data['demand_taxable_amount'] = 0;
            $data['demand_other_value'] = $data['demand_other_value'] ?? 0;
            $data = $business->getNoteFiscalCalculationTotal($data);

            return $this->repository->find($id)
                ->fill($data)
                ->save();
        }

        if (!array_key_exists('demand_taxable_manual', $data) && $id) {
            $data['demand_taxable_manual'] = 0;
            $data['demand_taxable_amount'] = $business->getTaxableAmount($data) / 100;
            $data['demand_other_value'] = $data['demand_other_value'] ?? 0;
            $data = $business->getNoteFiscalCalculationTotal($data);

            return $this->repository->find($id)
                ->fill($data)
                ->save();
        }
    }

    public function removeNotesSpreadsheetsAwardsAndDemands($id)
    {
        $this->repository->find($id)->notes()->delete();
        $this->repository->find($id)->spreadsheet()->delete();
        $this->repository->find($id)->awards()->delete();
        return $this->repository->find($id)
            ->delete();
    }
}
