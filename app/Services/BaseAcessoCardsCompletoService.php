<?php

namespace App\Services;

use App\Repositories\BaseAcessoCardsCompletoRepository;

class BaseAcessoCardsCompletoService extends Service
{
    protected $service;

    public function __construct(BaseAcessoCardsCompletoRepository $repository)
    {
        $this->service = $repository;
    }

    public function firstBaseAcessoCardNumberByDocument($document)
    {
        return $this->service->firstBaseAcessoCardNumberByDocument($document);
    }

    public function firstUnlikedBaseCardCompleto()
    {
        return $this->service->firstUnlikedBaseCardCompleto();
    }
}
