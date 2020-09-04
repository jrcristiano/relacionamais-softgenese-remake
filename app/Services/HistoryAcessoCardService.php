<?php

namespace App\Services;

use App\Repositories\HistoryAcessoCardRepository;

class HistoryAcessoCardService extends Service
{
    protected $service;

    public function __construct(HistoryAcessoCardRepository $repository)
    {
        $this->service = $repository;
    }

    public function findAcessoCardId($id)
    {
        return $this->service->findAcessoCardId($id);
    }
}