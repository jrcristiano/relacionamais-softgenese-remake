<?php

namespace App\Services;

use App\Repositories\AcessoCardRepository as AcessoCardRepo;

class AcessoCardService extends Service
{
    protected $service;

    public function __construct(AcessoCardRepo $repository)
    {
        $this->service = $repository;
    }

    public function storeCard($fileName, $demandId, $awardDemandId)
    {
        return $this->service->storeCard($fileName, $demandId, $awardDemandId);
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
}
