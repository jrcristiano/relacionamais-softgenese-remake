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

    public function findByDocumentWhereCardActive($document)
    {
        return $this->service->findByDocumentWhereCardActive($document);
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

    public function updateByParamWhereStatusNull(array $data, $param, $value)
    {
        return $this->service->updateByParamWhereStatusNull($data, $param, $value);
    }

    public function getBaseAcessoCardProxy($unlikedCard)
    {
        return $this->service->getBaseAcessoCardProxy($unlikedCard);
    }

    public function getBaseAcessoCardProxyByDocument($document)
    {
        return $this->service->getBaseAcessoCardProxyByDocument($document);
    }
}
