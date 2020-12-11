<?php

namespace App\Services;

use App\Repositories\AcessoCardShoppingRepository;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yidas\phpSpreadsheet\Helper;

class AcessoCardShoppingService extends Service
{
    protected $service, $baseAcessoCardsCompletoService;

    public function __construct(AcessoCardShoppingRepository $repository, BaseAcessoCardsCompraService $baseAcessoCardsCompletoService)
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

    public function findByProxy($proxy)
    {
        return $this->service->findByProxy($proxy);
    }

    public function findByAwardId($id)
    {
        return $this->service->findByAwardId($id);
    }

    public function findCardCancelledByAwardId($id)
    {
        return $this->service->findCardCancelledByAwardId($id);
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->service->saveByParam($data, $param, $value);
    }

    public function saveByParamWhereProxyNull(array $data, $param, $value)
    {
        return $this->service->saveByParamWhereProxyNull($data, $param, $value);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function getAcessoCardsWhereAwarded($id)
    {
        return $this->service->getAcessoCardsWhereAwarded($id);
    }

    public function getAcessoCardsWhereAwardedWithChargeback($id, $perPage = 200)
    {
        return $this->service->getAcessoCardsWhereAwardedWithChargeback($id);
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
        return $this->baseAcessoCardsCompletoService->getAcessoCardComprasNotGenerated($id);
    }

    public function getAcessoCardComprasNotGenerated($id)
    {
        return $this->baseAcessoCardsCompletoService->getAcessoCardComprasNotGenerated($id);
    }

    public function firstAcessoCardCompletoNotGenerated($id)
    {
        return $this->baseAcessoCardsCompletoService->firstAcessoCardCompletoNotGenerated($id);
    }

    public function getAwardedsAwaitingPayment($id)
    {
        $data = $this->baseAcessoCardsCompletoService->getAcessoCardComprasNotGenerated($id);

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

    public function updateAcessoCardsNotExists(array $data, $param, $value)
    {
        return $this->service->updateAcessoCardsNotExists($data, $param, $value);
    }

    public function getAllNewsAcessoCardsWhereAcessoCardAwardedId($id)
    {
        return $this->service->getAllNewsAcessoCardsWhereAcessoCardAwardedId($id);
    }

    public function findAcessoCardsWithoutCards($id)
    {
        return $this->service->findAcessoCardsWithoutCards($id);
    }

    public function getAcessoCardsAndBaseAcessoCardStatus()
    {
        return $this->service->getAcessoCardsAndBaseAcessoCardStatus();
    }
}
