<?php

namespace App\Services;

use App\Repositories\HistoryAcessoCardComprasRepository;
use Illuminate\Http\Request;

class HistoryAcessoCardComprasService extends Service
{
    protected $service;

    public function __construct(HistoryAcessoCardComprasRepository $repository)
    {
        $this->service = $repository;
    }

    public function findAcessoCardId($id)
    {
        return $this->service->findAcessoCardId($id);
    }

    public function getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id)
    {
        return $this->service->getInfoBaseAcessoCardsAndAcessoCardsByAwardId($id);
    }

    public function getInfoBaseAcessoCardsNotGeneratedAndAcessoCardsByAwardId($id)
    {
        return $this->service->getInfoBaseAcessoCardsNotGeneratedAndAcessoCardsByAwardId($id);
    }

    public function getAwardedsByAllAwards(Request $request)
    {
        return $this->service->queryAwardedsOfAllAwards($request);
    }

    public function getDataForFilters()
    {
        return $this->service->getDataForFilters();
    }
}
