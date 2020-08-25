<?php

namespace App\Services;

use App\Repositories\AcessoCardRepository as AcessoCardRepo;
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

    public function updateByAwardId(array $data, $document)
    {
        return $this->service->updateByAwardId($data, $document);
    }

    public function find($id)
    {
        return $this->service->find($id);
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
}
