<?php

namespace App\Services;

use App\Repositories\BaseAcessoCardsCompraRepository;

class BaseAcessoCardsCompraService extends Service
{
    protected $service;

    public function __construct(BaseAcessoCardsCompraRepository $repository)
    {
        $this->service = $repository;
    }

    public function findActiveCardByDocument($document)
    {
        return $this->service->findActiveCardByDocument($document);
    }

    public function findBaseAcessoCardActiveByDocument($document)
    {
        return $this->service->findBaseAcessoCardActiveByDocument($document);
    }

    public function findBaseAcessoCardActiveByProxy($proxy)
    {
        return $this->service->findBaseAcessoCardActiveByProxy($proxy);
    }

    public function findByProxy($proxy)
    {
        return $this->service->findByProxy($proxy);
    }

    public function firstBaseAcessoCardNumberByDocument($document)
    {
        return $this->service->firstBaseAcessoCardNumberByDocument($document);
    }

    public function firstUnlikedBaseCardCompleto()
    {
        return $this->service->firstUnlikedBaseCardCompleto();
    }

    public function getCollectionUnlikedBaseCardCompleto($quantity)
    {
        return $this->service->getCollectionUnlikedBaseCardCompleto($quantity);
    }

    public function findByDocumentWhereCardActive($document)
    {
        return $this->service->findByDocumentWhereCardActive($document);
    }

    public function getByDocumentWhereCardActive($document)
    {
        return $this->service->getByDocumentWhereCardActive($document);
    }

    public function getUnlikedBaseCardCompleto()
    {
        return $this->service->getUnlikedBaseCardCompleto();
    }

    public function getLikedAndUngenerateCards()
    {
        return $this->service->getLikedAndUngenerateCards();
    }

    public function findByCard($card)
    {
        return $this->service->findByCard($card);
    }

    public function saveByDocument(array $data, $document)
    {
        return $this->service->saveByDocument($data, $document);
    }

    public function saveByDocumentActive(array $data, $document)
    {
        return $this->service->saveByDocumentActive($data, $document);
    }

    public function saveByParam(array $data, $param, $value)
    {
        return $this->service->saveByParam($data, $param, $value);
    }

    public function getAcessoCardCompletoNotGenerated($id)
    {
        return $this->service->getAcessoCardCompletoNotGenerated($id);
    }

    public function getAcessoCardCompletoNotGeneratedView()
    {
        return $this->service->getAcessoCardCompletoNotGeneratedView();
    }

    public function firstAcessoCardCompletoNotGenerated($id)
    {
        return $this->service->firstAcessoCardCompletoNotGenerated($id);
    }

    public function findWhereStatusByDocument($status, $document)
    {
        return $this->service->findWhereStatusByDocument($status, $document);
    }

    public function updateByParamWhereStatusNull(array $data, $param = 'base_acesso_card_status', $value = null)
    {
        return $this->service->updateByParamWhereStatusNull($data, $param, $value);
    }

    public function updateByParamWhereStatusAndNameNull(array $data, $param = 'base_acesso_card_status', $value = null)
    {
        return $this->service->updateByParamWhereStatusAndNameNull($data, $param, $value);
    }

    public function getBaseAcessoCardProxy($unlikedCard)
    {
        return $this->service->getBaseAcessoCardProxy($unlikedCard);
    }

    public function getBaseAcessoCardProxyByDocument($document)
    {
        return $this->service->getBaseAcessoCardProxyByDocument($document);
    }

    public function updateWhereStatusNotCancelledAndStatusNotReserved(array $data, $param, $value)
    {
        return $this->service->updateWhereStatusNotCancelledAndStatusNotReserved($data, $param, $value);
    }

    public function getAllActiveCards()
    {
        return $this->service->getAllActiveCards();
    }

    public function getBaseAcessoCardActiveByDocument($document)
    {
        return $this->service->getBaseAcessoCardActiveByDocument($document);
    }

    public function firstBaseAcessoCardActiveByDocument($document, $id)
    {
        return $this->service->firstBaseAcessoCardActiveByDocument($document, $id);
    }

    public function firstBaseAcessoCardInativeByDocument($document, $id)
    {
        return $this->service->firstBaseAcessoCardInativeByDocument($document, $id);
    }

    public function getBaseAcessoCardInativeByDocument($document)
    {
        return $this->service->getBaseAcessoCardInativeByDocument($document);
    }

    public function getCurrencyCardById($id, $callCenterId)
    {
        return $this->service->getCurrencyCardById($id, $callCenterId);
    }

    public function getPreviousCardById($id, $callCenterId)
    {
        return $this->service->getPreviousCardById($id, $callCenterId);
    }
}
