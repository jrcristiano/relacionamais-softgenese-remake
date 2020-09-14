<?php

namespace App\Services;

use App\Repositories\AcessoCardRepository as AcessoCardRepo;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yidas\phpSpreadsheet\Helper;

class AcessoCardService extends Service
{
    protected $service;

    public function __construct(AcessoCardRepo $repository)
    {
        $this->service = $repository;
    }

    public function storeCard($fileName, $demandId, $awardDemandId, $params)
    {
        return $this->service->storeCard($fileName, $demandId, $awardDemandId, $params);
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

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function getAcessoCardsWhereAwarded($id)
    {
        return $this->service->getAcessoCardsWhereAwarded($id);
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

    public function getAwardedsAwaitingPayment($id)
    {
        $data = $this->service->getAwardedsAwaitingPayment($id);

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'PROXY');
        $sheet->setCellValue('B1', 'CPF');
        $sheet->setCellValue('C1', 'NOME');

        foreach ($data as $key => $value) {
            $key = $key + 2;
            $sheet->setCellValue("A{$key}", $value->base_acesso_card_proxy);
            $sheet->setCellValue("B{$key}", $value->acesso_card_document, DataType::TYPE_STRING);
            $sheet->setCellValue("C{$key}", $value->acesso_card_name);
        }

        $writer = new Xlsx($spreadsheet);

        $path = storage_path();
        $awardId = $data[0]->acesso_card_award_id;
        $awardId = str_pad($awardId, 2, '0', STR_PAD_LEFT);

        $storageFileName = "{$path}/app/public/shipments/TODOSVINC{$awardId}.xlsx";

        $writer->save($storageFileName);

        return "TODOSVINC{$awardId}.xlsx";
    }

    public function getAwardedsAwaitingPaymentNotGenerated($id)
    {
        $data = $this->service->getAwardedsAwaitingPaymentNotGenerated($id);

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'PROXY');
        $sheet->setCellValue('B1', 'CPF');
        $sheet->setCellValue('C1', 'NOME');

        foreach ($data as $key => $value) {
            $key = $key + 2;
            $sheet->setCellValue("A{$key}", $value->base_acesso_card_proxy);
            $sheet->setCellValue("B{$key}", $value->acesso_card_document, DataType::TYPE_STRING);
            $sheet->setCellValue("C{$key}", $value->acesso_card_name);
        }

        $writer = new Xlsx($spreadsheet);

        $path = storage_path();
        $awardId = str_pad($id, 2, '0', STR_PAD_LEFT);

        $storageFileName = "{$path}/app/public/shipments/VINC{$id}.xlsx";

        $writer->save($storageFileName);

        return "VINC{$awardId}.xlsx";
    }
}
