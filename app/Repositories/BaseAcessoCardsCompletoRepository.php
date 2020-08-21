<?php

namespace App\Repositories;

use App\BaseAcessoCardsCompleto;

class BaseAcessoCardsCompletoRepository extends Repository
{
    protected $repository;

    public function __construct(BaseAcessoCardsCompleto $baseAcessoCardsCompleto)
    {
        $this->repository = $baseAcessoCardsCompleto;
    }

    public function findByDocument($document)
    {
        return $this->repository->where('base_acesso_card_cpf', $document)
            ->first();
    }

    public function firstBaseAcessoCardNumberByDocument($document)
    {
        return $this->repository->select('base_acesso_card_number')
            ->where('base_acesso_card_cpf', $document)
            ->first();
    }

    public function firstUnlikedBaseCardCompleto()
    {
        return $this->repository->whereNull('base_acesso_card_cpf')
            ->first();
    }
}
