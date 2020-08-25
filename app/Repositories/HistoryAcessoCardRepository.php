<?php

namespace App\Repositories;

use App\HistoryAcessoCard;

class HistoryAcessoCardRepository extends Repository
{
    protected $repository;

    public function __construct(HistoryAcessoCard $repository)
    {
        $this->repository = $repository;
    }
}
