<?php

namespace App\Repositories;

use App\Provider;

class ProviderRepository extends Repository
{
    protected $repository;

    public function __construct(Provider $provider)
    {
        $this->repository = $provider;
    }

    public function getProvidersByPaginate($pages)
    {
        return $this->repository->orderBy('providers.id', 'desc')
            ->paginate($pages);
    }

    public function getProviderNameAndId()
    {
        $providers = $this->repository->select(['id', 'provider_name'])
            ->orderBy('providers.id', 'desc');

        return $providers->get();
    }
}
