<?php

namespace App\Repositories;

use App\Transfer;

class TransferRepository extends Repository
{
    protected $repository;

    public function __construct(Transfer $transfer)
    {
        $this->repository = $transfer;
    }

    public function getTransfersByPaginate($perPage = 50)
    {
        return $this->repository->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
