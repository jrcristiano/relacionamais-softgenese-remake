<?php

namespace App\Repositories;

use App\Spreadsheet;
use yidas\phpSpreadsheet\Helper;

class SpreadsheetRepository extends Repository
{
    protected $repository;

    public function __construct(Spreadsheet $repository)
    {
        $this->repository = $repository;
    }

    public function getSpreadsheetsWhereAwarded($id, $perPage = 200)
    {
        return $this->repository->select([
            'spreadsheets.*',
            'shipments_api.shipment_generated',
            'spreadsheets.spreadsheet_chargeback',
            'awards.awarded_status',
            ])
            ->leftJoin('shipments_api', 'spreadsheets.spreadsheet_award_id', '=', 'shipments_api.shipment_award_id')
            ->leftJoin('awards', 'spreadsheets.spreadsheet_award_id', '=', 'awards.id')
            ->where('spreadsheets.spreadsheet_award_id', $id)
            ->paginate($perPage);
    }

    public function getShipments($id)
    {
        return $this->repository->select([
            'spreadsheets.*',
        ])
        ->where('spreadsheet_award_id', $id)
        ->whereNull('awards.awarded_shipment_cancelled')
        ->leftJoin('awards', 'spreadsheets.spreadsheet_award_id', '=', 'awards.id')
        ->get()
        ->toArray();
    }

    public function storeShipment(string $fileName, $demandId, $id)
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        $data = [];
        foreach ($excel as $key => $row) {

            if ($row[0] != null) {
                $key += 1;

                $documentNumber = str_pad($row[1], 1, '0', STR_PAD_LEFT);
                $data['spreadsheet_name'] = $row[0] ?? null;
                $data['spreadsheet_document'] = $documentNumber ?? null;
                $data['spreadsheet_value'] = $row[2] ?? null;
                $data['spreadsheet_bank'] = $row[3] ?? null;
                $data['spreadsheet_agency'] = $row[4] ?? null;
                $data['spreadsheet_account'] = $row[5] ?? null;
                $data['spreadsheet_account_type'] = $row[6] ?? 'C';
                $data['spreadsheet_award_id'] = $id;
                $data['spreadsheet_keyline'] = $key;
                $data['spreadsheet_shipment_file_path'] = $fileName;
                $data['spreadsheet_demand_id'] = $demandId;

                $this->repository->create($data);
            }
        }

        return false;
    }

    public function getDataToGenerationShipmentArray()
    {
        return $this->repository->select([
            'spreadsheet_name',
            'spreadsheet_document',
            'spreadsheet_value',
            'spreadsheet_bank',
            'spreadsheet_agency',
            'spreadsheet_account',
            'spreadsheet_account_type',
            'spreadsheet_award_id'
        ])
        ->where('spreadsheet_award_id', 2)
        ->get()
        ->toArray();
    }

    public function getSpreadsheetsRows(string $fileName)
    {
        return Helper::newSpreadsheet($fileName)->getRows();
    }

    public function getData($id)
    {
        return $this->repository->select([
            'spreadsheets.spreadsheet_keyline',
            'spreadsheets.id as id',
            'spreadsheets.spreadsheet_name',
            'spreadsheets.spreadsheet_document',
            'spreadsheets.spreadsheet_value',
            'spreadsheets.spreadsheet_bank',
            'spreadsheets.spreadsheet_agency',
            'spreadsheets.spreadsheet_account',
            'spreadsheets.spreadsheet_account_type',
            'awards.id as awarded_id',
            'awards.awarded_upload_table'
        ])
        ->leftJoin('awards', 'spreadsheets.spreadsheet_award_id', '=', 'awards.id')
        ->where('spreadsheets.id', '=', $id)
        ->first();
    }

    public function getSpreadsheetFileAndSpreadsheetKeyLine($id)
    {
        return $this->repository->select([
            'spreadsheets.id',
            'spreadsheets.spreadsheet_keyline',
            'awards.awarded_upload_table'
        ])
        ->join('awards', 'spreadsheets.spreadsheet_award_id', '=', 'awards.id')
        ->where('spreadsheets.id', $id)
        ->first();
    }

    public function getPathByAwardedUploadTable($id)
    {
        return $this->repository->select('awarded_upload_id')->where('id', $id)
            ->get();
    }
}
