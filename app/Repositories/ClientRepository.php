<?php

namespace App\Repositories;

use App\Client;
use App\Helpers\Number;

class ClientRepository extends Repository
{
    protected $repository;

    public function __construct(Client $client)
    {
        $this->repository = $client;
    }

    public function getClientsByPaginate($pages)
    {
        return $this->repository->orderBy('clients.id', 'desc')
            ->paginate($pages);
    }

    public function all()
    {
        return $this->repository->orderBy('clients.id', 'desc')
            ->get();
    }

    public function getData($data)
    {
        $data = is_numeric($data) ? (float) $data : (string) $data;
        return $this->repository->select(['client_company', 'client_rate_admin', 'client_cnpj'])
            ->where('client_cnpj', $data)
            ->get();
    }

    public function getCalculation($awardValue, $cnpj)
    {
        $taxableValue = $this->repository->select(['client_rate_admin'])
            ->where('client_cnpj', $cnpj)
            ->get();

        $taxableValue = (float) $taxableValue[0]->client_rate_admin;
        $calculation = ($taxableValue * $awardValue);
        $calculation = toReal($calculation);
        return collect(['demand_taxable_amount' => $calculation]);
    }

    public function getCompanyWithCnpj()
    {
        return $this->repository->select(['client_company', 'client_cnpj'])
            ->orderBy('clients.id', 'desc')
            ->get();
    }
}
