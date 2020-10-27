<?php

namespace App\Services;

use App\Repositories\PartAcessoCardRepository;

class PartAcessoCardService extends Service
{
    protected $service;

    public function __construct(PartAcessoCardRepository $service)
    {
        $this->service = $service;
    }
}
