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

    public function findByProxy($proxy)
    {
        return $this->repository->where('base_acesso_card_proxy', $proxy)
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

    public function findBaseAcessoCardActiveByDocument($document)
    {
        return $this->repository->select('id')
            ->where('base_acesso_card_cpf', $document)
            ->where('base_acesso_card_status', 1)
            ->first();
    }

    public function findBaseAcessoCardActiveByProxy($proxy)
    {
        return $this->repository->where('base_acesso_card_proxy', $proxy)
            ->where('base_acesso_card_status', 1)
            ->first();
    }

    public function getBaseAcessoCardActiveByDocument($document)
    {
        return $this->repository
            ->where('base_acesso_card_cpf', $document)
            ->where('base_acesso_card_status', 1)
            ->orderBy('base_acesso_cards_completo.updated_at', 'desc')
            ->get();
    }

    public function firstBaseAcessoCardActiveByDocument($document, $id)
    {
        return $this->repository
            ->leftJoin('base_acesso_cards_completo_orders', 'base_acesso_cards_completo.id', '=', 'base_acesso_cards_completo_orders.currency_card_id')
            ->leftJoin('call_centers', 'base_acesso_cards_completo_orders.call_center_id', '=', 'call_centers.id')
            ->where('base_acesso_card_cpf', $document)
            ->orderBy('base_acesso_cards_completo.updated_at', 'desc')
            ->where('base_acesso_cards_completo_orders.call_center_id', $id)
            ->first();
    }

    public function firstBaseAcessoCardInativeByDocument($document, $id)
    {
        return $this->repository
            ->leftJoin('base_acesso_cards_completo_orders', 'base_acesso_cards_completo.id', '=', 'base_acesso_cards_completo_orders.previous_card_id')
            ->leftJoin('call_centers', 'base_acesso_cards_completo_orders.call_center_id', '=', 'call_centers.id')
            ->where('base_acesso_card_cpf', $document)
            ->orderBy('base_acesso_cards_completo.updated_at', 'desc')
            ->where('base_acesso_cards_completo_orders.call_center_id', $id)
            ->first();
    }

    public function getBaseAcessoCardInativeByDocument($document)
    {
        return $this->repository
            ->where('base_acesso_card_cpf', $document)
            ->where('base_acesso_card_status', 2)
            ->orderBy('base_acesso_cards_completo.updated_at', 'desc')
            ->get();
    }

    public function firstUnlikedBaseCardCompleto()
    {
        return $this->repository->whereNull('base_acesso_card_cpf')
            ->where('base_acesso_card_status', null)
            ->whereNull('base_acesso_card_status')
            ->first();
    }

    public function getCollectionUnlikedBaseCardCompleto($quantity)
    {
        return $this->repository->whereNull('base_acesso_card_cpf')
            ->where('base_acesso_card_status', 1)
            ->orWhereNull('base_acesso_card_status')
            ->whereNull('base_acesso_card_status')
            ->take($quantity)
            ->get();
    }

    public function findByDocumentWhereCardActive($document)
    {
        return $this->repository->where('base_acesso_card_status', 1)
            ->where('base_acesso_card_cpf', $document)
            ->first();
    }

    public function getByDocumentWhereCardActive($document)
    {
        return $this->repository->where('base_acesso_card_status', 1)
            ->where('base_acesso_card_cpf', $document)
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

    public function saveByDocumentActive($data, $document)
    {
        return $this->repository->where('base_acesso_card_cpf', $document)
            ->where('base_acesso_card_status', 1)
            ->update($data);
    }

    public function updateByParamWhereStatusNull(array $data, $param = 'base_acesso_card_status', $value = null)
    {
        return $this->repository->whereNull('base_acesso_card_status')
            ->where($param, $value)
            ->update($data);
    }

    public function updateByParamWhereStatusAndNameNull(array $data, $param = 'base_acesso_card_status', $value = null)
    {
        return $this->repository->whereNull('base_acesso_card_status')
            ->whereNull('base_acesso_card_name')
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

    public function getCurrencyCardById($id, $callCenterId)
    {
        return $this->repository->select('base_acesso_cards_completo.base_acesso_card_proxy')
            ->join('base_acesso_cards_completo_orders', 'base_acesso_cards_completo.id', '=', 'base_acesso_cards_completo_orders.currency_card_id')
            ->orderBy('base_acesso_cards_completo.id', 'desc')
            ->where('base_acesso_cards_completo_orders.previous_card_id', $id)
            ->where('base_acesso_cards_completo_orders.call_center_id', $callCenterId)
            ->first();
    }

    public function getPreviousCardById($id, $callCenterId)
    {
        return $this->repository->select('base_acesso_cards_completo.base_acesso_card_proxy')
            ->join('base_acesso_cards_completo_orders', 'base_acesso_cards_completo.id', '=', 'base_acesso_cards_completo_orders.previous_card_id')
            ->orderBy('base_acesso_cards_completo.id', 'desc')
            ->where('base_acesso_cards_completo_orders.previous_card_id', $id)
            ->where('base_acesso_cards_completo_orders.call_center_id', $callCenterId)
            ->first();
    }
}
