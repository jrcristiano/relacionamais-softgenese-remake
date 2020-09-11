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
        return $this->repository->select(['id', 'base_acesso_card_number'])
            ->where('base_acesso_card_cpf', $document)
            ->first();
    }

    public function firstUnlikedBaseCardCompleto()
    {
        return $this->repository->whereNull('base_acesso_card_cpf')
            ->first();
    }

    public function saveByDocument($data, $document)
    {
        return $this->repository->where('base_acesso_card_cpf', $document)
            ->update($data);
    }

    public function findByCard($card)
    {
        return $this->repository->select([
            'base_acesso_cards_completo.id',
            'acesso_cards.acesso_card_value',
            'base_acesso_cards_completo.base_acesso_card_proxy'
        ])
        ->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_number', '=', 'acesso_cards.acesso_card_number')
        ->where('base_acesso_card_number', $card)
        ->first();
    }
}
