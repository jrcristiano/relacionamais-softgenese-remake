<?php

namespace App\Services;

use App\Repositories\AcessoCardRepository as AcessoCardRepo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yidas\phpSpreadsheet\Helper;
use Illuminate\Http\Request;

class AcessoCardService extends Service
{
    protected $service, $baseAcessoCardsCompletoService;

    public function __construct(AcessoCardRepo $repository, BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->service = $repository;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
    }

    public function storeCard($fileName, $demandId, $awardDemandId, $params)
    {
        return $this->service->storeCard($fileName, $demandId, $awardDemandId, $params);
    }

    public function findInfoAcessoCard($document)
    {
        return $this->service->findInfoAcessoCard($document);
    }

    public function updateByDocument(array $data, $document)
    {
        return $this->service->updateByDocument($data, $document);
    }

    public function find($id)
    {
        return $this->service->find($id);
    }

    public function findByCard($card)
    {
        return $this->service->findByCard($card);
    }

    public function findByAwardId($id)
    {
        return $this->service->findByAwardId($id);
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->service->saveByParam($data, $param, $value);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function getAcessoCardsWhereAwarded($id)
    {
        return $this->service->getAcessoCardsWhereAwarded($id);
    }

    public function getAwardedsByAllAwards(Request $request)
    {
        return $this->service->getAwardedsByAllAwards($request);
    }

    public function findByDocument($document)
    {
        return $this->service->findByDocument($document);
    }

    public function getHistoriesByDocument($document)
    {
        return $this->service->getHistoriesByDocument($document);
    }

    public function getAcessoCardByDocument($document)
    {
        return $this->service->getAcessoCardByDocument($document);
    }

    public function getData($fileName, $pos)
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        $data = [];
        foreach ($excel as $key => $row) {
            $data[] = $row[$pos];
        }

        return $data;
    }

    public function getAcessoCardCompletoNotGenerated($id)
    {
        return $this->baseAcessoCardsCompletoService->getAcessoCardCompletoNotGenerated($id);
    }

    public function firstAcessoCardCompletoNotGenerated($id)
    {
        return $this->baseAcessoCardsCompletoService->firstAcessoCardCompletoNotGenerated($id);
    }

    public function getAwardedsAwaitingPayment($id)
    {
        $data = $this->baseAcessoCardsCompletoService->getAcessoCardCompletoNotGenerated($id);

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'PROXY');
        $sheet->setCellValue('B1', 'CPF');
        $sheet->setCellValue('C1', 'NOME');

        foreach ($data as $key => $value) {
            $key = $key + 2;
            $proxy = $value->base_acesso_card_proxy;

            $sheet->setCellValueExplicit(
                "A{$key}",
                $proxy,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValueExplicit(
                "B{$key}",
                $value->acesso_card_document,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue("C{$key}", $value->acesso_card_name);
        }

        $writer = new Xlsx($spreadsheet);

        $path = storage_path();
        $awardId = str_pad($id, 2, '0', STR_PAD_LEFT);

        $field = \App\ShipmentApi::select('shipment_last_field')
            ->where('shipment_award_id', $awardId)
            ->first();

        $field = str_pad($field->shipment_last_field, 2, '0', STR_PAD_LEFT);

        $storageFileName = "{$path}/app/public/shipments/TODOSVINC{$field}.xlsx";

        $writer->save($storageFileName);

        return "TODOSVINC{$field}.xlsx";
    }

    public function getAwardedsAwaitingPaymentNotGenerated($id)
    {
        return $this->service->getAwardedsAwaitingPaymentNotGenerated($id);
    }

    public function chargebackAllNewsAcessoCard($id)
    {
        return $this->service->chargebackAllNewsAcessoCard($id);
    }

    public function getAllPartedAcessoCards($id)
    {
        return $this->service->getAllPartedAcessoCards($id);
    }

    public function updateAcessoCardsAlreadyExists(array $data, $param, $value)
    {
        return $this->service->updateAcessoCardsAlreadyExists($data, $param, $value);
    }

    public function getAllNewsAcessoCardsWhereAcessoCardAwardedId($id)
    {
        return $this->service->getAllNewsAcessoCardsWhereAcessoCardAwardedId($id);
    }
}
