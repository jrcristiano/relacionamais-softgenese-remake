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

    public function findActiveCardByDocument($document)
    {
        return $this->repository->where('base_acesso_card_cpf', $document)
            ->where('base_acesso_card_status', 1)
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
            ->where('base_acesso_card_status', 1)
            ->orWhereNull('base_acesso_card_status')
            ->whereNull('base_acesso_card_status')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getCollectionUnlikedBaseCardCompleto($quantity)
    {
        return $this->repository->whereNull('base_acesso_card_cpf')
            ->where('base_acesso_card_status', 1)
            ->orWhereNull('base_acesso_card_status')
            ->whereNull('base_acesso_card_status')
            ->take($quantity)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findByDocumentWhereCardActive($document)
    {
        return $this->repository->where('base_acesso_card_status', 1)
            ->where('base_acesso_card_cpf', $document)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getByDocumentWhereCardActive($document)
    {
        return $this->repository->where('base_acesso_card_status', 1)
            ->where('base_acesso_card_cpf', $document)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getUnlikedBaseCardCompleto()
    {
        return $this->repository->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_cpf', '=', 'acesso_cards.acesso_card_document')
            ->whereNull('base_acesso_card_generated')
            ->whereNotNull('base_acesso_cards_completo.base_acesso_card_name')
            ->whereNotNull('base_acesso_cards_completo.base_acesso_card_cpf')
            ->where('base_acesso_card_status', 1)
            ->get();
    }

    public function findWhereStatusByDocument($status, $document)
    {
        return $this->repository->select('*')
            ->where('base_acesso_card_status', $status)
            ->where('base_acesso_card_cpf', $document)
            ->first();
    }

    public function getUngenerateCards()
    {
        return $this->repository->whereNull('base_acesso_card_generated')
            ->get();
    }

    public function getLikedAndUngenerateCards()
    {
        return $this->repository->whereNull('base_acesso_card_generated')
            ->whereNotNull('base_acesso_card_name')
            ->whereNotNull('base_acesso_card_cpf')
            ->get();
    }

    public function getBaseAcessoCardProxy($unlikedCard)
    {
        return $this->repository->select('base_acesso_card_proxy')
            ->where('base_acesso_card_number', $unlikedCard)
            ->where('base_acesso_card_status', 1)
            ->orWhereNull('base_acesso_card_status')
            ->orWhereNull('base_acesso_card_status')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getBaseAcessoCardProxyByDocument($document)
    {
        return $this->repository->select('base_acesso_card_proxy')
            ->where('base_acesso_card_cpf', $document)
            ->where('base_acesso_card_status', 1)
            ->first();
    }

    public function saveByDocument($data, $document)
    {
        return $this->repository->where('base_acesso_card_cpf', $document)
            ->update($data);
    }

    public function updateByParamWhereStatusNull(array $data, $param = 'base_acesso_card_status', $value = null)
    {
        return $this->repository->whereNull('base_acesso_card_status')
            ->where($param, $value)
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

    public function getAcessoCardCompletoNotGenerated($id)
    {
        return $this->repository->select([
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'base_acesso_cards_completo.id',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'base_acesso_cards_completo.base_acesso_card_proxy',
            'acesso_cards.acesso_card_generated'
        ])
        ->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_proxy', '=', 'acesso_cards.acesso_card_proxy')
        ->whereNull('base_acesso_card_generated')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_name')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_cpf')
        ->whereNull('acesso_card_chargeback')
        ->where('acesso_cards.acesso_card_award_id', $id)
        ->where('base_acesso_cards_completo.base_acesso_card_status', 1)
        ->get();
    }

    public function getAcessoCardCompletoNotGeneratedView()
    {
        return $this->repository->select([
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'base_acesso_cards_completo.id',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'base_acesso_cards_completo.base_acesso_card_proxy',
            'acesso_cards.acesso_card_generated'
        ])
        ->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_proxy', '=', 'acesso_cards.acesso_card_proxy')
        ->whereNull('base_acesso_card_generated')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_name')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_cpf')
        ->where('base_acesso_cards_completo.base_acesso_card_status', 1)
        ->where('acesso_cards.acesso_card_generated', 1)
        ->get();
    }

    public function firstAcessoCardCompletoNotGenerated($id)
    {
        return $this->repository->select([
            'acesso_cards.acesso_card_name',
            'acesso_cards.acesso_card_document',
            'base_acesso_cards_completo.id',
            'base_acesso_cards_completo.base_acesso_card_cpf',
            'base_acesso_cards_completo.base_acesso_card_proxy',
            'acesso_cards.acesso_card_generated'
        ])
        ->leftJoin('acesso_cards', 'base_acesso_cards_completo.base_acesso_card_cpf', '=', 'acesso_cards.acesso_card_document')
        ->whereNull('base_acesso_card_generated')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_name')
        ->whereNotNull('base_acesso_cards_completo.base_acesso_card_cpf')
        ->where('acesso_cards.acesso_card_award_id', $id)
        ->first();
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->repository->where($param, $value)
            ->update($data);
    }

    public function updateWhereStatusNotCancelledAndStatusNotReserved(array $data, $param, $value)
    {
        return $this->repository->where('base_acesso_card_status', '!=', 2)
            ->where('base_acesso_card_status', '!=', 3)
            ->whereNull('base_acesso_card_status')
            ->where($param, $value)
            ->update($data);
    }
}
