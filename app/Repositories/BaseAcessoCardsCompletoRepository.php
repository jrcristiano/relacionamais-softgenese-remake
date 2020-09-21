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
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getUnlikedBaseCardCompleto()
    {
        return $this->repository->whereNotNull('base_acesso_card_name')
            ->whereNotNull('base_acesso_card_cpf')
            ->whereNull('base_acesso_card_generated')
            ->get();
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

    public function getAcessoCardCompletoNotGenerated()
    {
        return $this->repository->select([
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'base_acesso_cards_completo.id',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'awaiting_payments.awaiting_payment_all_file',
            'base_acesso_cards_completo.base_acesso_card_proxy',
            'acesso_cards.acesso_card_generated'
        ])
        ->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_cpf', '=', 'acesso_cards.acesso_card_document')
        ->leftJoin('awaiting_payments', 'acesso_cards.acesso_card_award_id', '=', 'awaiting_payments.awaiting_payment_award_id')
        ->whereNull('base_acesso_card_generated')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_name')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_cpf')
        ->get();
    }

    public function getAlert()
    {
        return $this->repository->select([
            'awaiting_payments.awaiting_payment_all_file',
            'acesso_cards.acesso_card_award_id',
            'acesso_cards.acesso_card_generated'
        ])
        ->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_cpf', '=', 'acesso_cards.acesso_card_document')
        ->leftJoin('awaiting_payments', 'acesso_cards.acesso_card_award_id', '=', 'awaiting_payments.awaiting_payment_award_id')
        ->whereNull('base_acesso_card_generated')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_name')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_cpf')
        ->groupBy('awaiting_payment_all_file')
        ->first();
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->update($data);
    }
}
