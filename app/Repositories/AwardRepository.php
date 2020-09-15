<?php

namespace App\Repositories;

use App\Award;
use App\CashFlow;
use App\Spreadsheet;
use Illuminate\Support\Facades\DB;

class AwardRepository extends Repository
{
    protected $repository;
    private $cashFlowRepo;
    private $spreadsheetRepo;

    public function __construct(Award $award, CashFlow $cashFlow, Spreadsheet $spreadsheet)
    {
        $this->repository = $award;
        $this->cashFlowRepo = $cashFlow;
        $this->spreadsheetRepo = $spreadsheet;
    }

    public function getAwardsWithColumnShipmentGenerated($id, $perPage = 200)
    {
        return $this->repository->select([
            'awards.*',
            'shipments_api.shipment_generated',
            'awaiting_payments.awaiting_payment_file',
        ])
        ->leftJoin('awaiting_payments', 'awards.id', '=', 'awaiting_payments.awaiting_payment_award_id')
        ->leftJoin('shipments_api', 'awards.id', '=', 'shipments_api.shipment_award_id')
        ->where('awards.awarded_demand_id', $id)
        ->paginate($perPage);
    }

    public function getShipmentsbyPaginate($perPage = 100, $awardType = null)
    {
        $query = $this->repository->select([
            'awards.*',
            'shipments_api.shipment_generated',
            'awaiting_payments.awaiting_payment_all_file',
            'awaiting_payments.awaiting_payment_file',
        ])
        ->where('awarded_status', '=', 1)
        ->whereNotNull('awarded_upload_table')
        ->orderBy('id', 'desc')
        ->leftJoin('shipments_api', 'awards.id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('awaiting_payments', 'awards.id', '=', 'awaiting_payments.awaiting_payment_award_id');

        if ($awardType) {
            $query->where('awarded_type', $awardType);
        }

        return $query->paginate($perPage);
    }

    public function getFirstPathByAwardedUploadSpreadsheet($id)
    {
        return $this->repository->select(['awarded_upload_table'])
            ->where('id', $id)
            ->first();
    }

    public function firstFileNameById($id)
    {
        return $this->repository->select('awarded_upload_table')
            ->where('id', $id)
            ->first();
    }

    public function verifyIfSpreadsheetsExists($id)
    {
        return $this->repository->select(['awarded_upload_table'])
            ->where('id', $id)
            ->first();
    }

    public function getAwardedValueWhereAwardDemandId($id)
    {
        return $this->repository->select('awarded_value as sale')->where('awarded_demand_id', $id)
            ->first();
    }

    public function getAwardSale($id)
    {
        return $this->repository->select(DB::raw('sum(awards.awarded_value) as award_sale'))
            ->where('award_demand_id', $id)
            ->first();
    }

    public function getBankId($id)
    {
        return $this->repository->select('awarded_bank_id as bank_id')->where('id', $id)
            ->first();
    }

    public function removeAwardAndSpreadsheetAndAwardsInCashFlows($id)
    {
        $this->cashFlowRepo->where('flow_award_id', $id)
            ->delete();

        $this->spreadsheetRepo->where('spreadsheet_award_id', $id)
            ->delete();

        return $this->repository->find($id)
            ->delete();
    }
}
