<?php

namespace App\Repositories;

use App\AcessoCard;
use App\Award;
use App\CashFlow;
use App\Spreadsheet;
use Illuminate\Support\Facades\DB;

class AwardRepository extends Repository
{
    protected $repository;
    private $cashFlowRepo;
    private $spreadsheetRepo;
    private $acessoCardRepo;

    public function __construct(Award $award, CashFlow $cashFlow, Spreadsheet $spreadsheet, AcessoCard $acessoCard)
    {
        $this->repository = $award;
        $this->cashFlowRepo = $cashFlow;
        $this->spreadsheetRepo = $spreadsheet;
        $this->acessoCardRepo = $acessoCard;
    }

    public function getAwardsWithColumnShipmentGenerated($id, $perPage = 200)
    {
        return $this->repository->select([
            'awards.*',
            'shipments_api.shipment_generated',
            'shipments_api.shipment_file_vinc',
        ])
        ->leftJoin('shipments_api', 'awards.id', '=', 'shipments_api.shipment_award_id')
        ->where('awards.awarded_demand_id', $id)
        ->paginate($perPage);
    }

    public function getShipmentsbyPaginate($perPage = 100, $awardType = null)
    {
        $query = $this->repository->select([
            'awards.*',
            'notes.note_number',
            'shipments_api.shipment_generated',
            'shipments_api.shipment_file_vinc',
            'shipments_api.shipment_file_vinc_generated',
        ])
        ->where('awarded_status', '=', 1)
        ->whereNotNull('awarded_upload_table')
        ->orderBy('id', 'desc')
        ->leftJoin('shipments_api', 'awards.id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('notes', 'awards.awarded_demand_id', '=', 'notes.note_demand_id');

        if ($awardType) {
            $query->where('awarded_type', $awardType);
        }

        return $query->paginate($perPage);
    }

    public function getAlerts()
    {
        return $this->repository->select([
            'awards.*',
            'notes.note_number',
            'shipments_api.shipment_generated',
            'shipments_api.shipment_file_vinc',
            'shipments_api.shipment_file_vinc_generated',
        ])
        ->where('awarded_status', '=', 1)
        ->whereNotNull('shipment_file_vinc')
        ->whereNull('shipment_file_vinc_generated')
        ->leftJoin('shipments_api', 'awards.id', '=', 'shipments_api.shipment_award_id')
        ->leftJoin('notes', 'awards.awarded_demand_id', '=', 'notes.note_demand_id')
        ->groupBy('shipment_file_vinc')
        ->orderBy('id', 'desc')
        ->first();
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

        $awardDepositAccount = $this->spreadsheetRepo->where('spreadsheet_award_id', $id)->first();
        if ($awardDepositAccount) {
            $this->spreadsheetRepo->where('spreadsheet_award_id', $id)
                ->delete();
        }

        $awardAcessoCard = $this->acessoCardRepo->where('acesso_card_award_id', $id)->first();
        if ($awardAcessoCard) {
            $this->acessoCardRepo->where('acesso_card_award_id', $id)
                ->delete();
        }

        return $this->repository->find($id)
            ->delete();
    }
}
