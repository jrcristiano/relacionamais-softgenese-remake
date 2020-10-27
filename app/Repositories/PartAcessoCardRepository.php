<?php

namespace App\Repositories;

use App\PartAcessoCard;

class PartAcessoCardRepository extends Repository
{
    protected $repository;

    public function __construct(PartAcessoCard $partAcessoCard)
    {
        $this->repository = $partAcessoCard;
    }
}
