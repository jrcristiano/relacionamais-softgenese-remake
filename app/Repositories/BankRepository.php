<?php

namespace App\Repositories;

use App\Bank;

class BankRepository extends Repository
{
    protected $repository;

    public function __construct(Bank $bank)
    {
        $this->repository = $bank;
    }

    public function getBanksByPaginate($pages)
    {
        return $this->repository->orderBy('banks.id', 'desc')
            ->paginate($pages);
    }

    public function getBanks()
    {
        $banks = $this->repository->select(['id', 'bank_name', 'bank_account', 'bank_agency'])
            ->orderBy('banks.id', 'desc');

        return $banks->get();
    }

    public function getBankIdByAccount($account)
    {
        return $this->repository->select('id')
            ->where('bank_account', $account)
            ->first();
    }

    public function getBankById($id)
    {
        return $this->repository->select(['id', 'bank_name', 'bank_account', 'bank_agency'])
            ->where('banks.id', $id)
            ->first();
    }
}
