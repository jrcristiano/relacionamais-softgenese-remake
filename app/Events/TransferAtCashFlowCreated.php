<?php

namespace App\Events;

use App\Repositories\CashFlowRepository as CashFlowRepo;

class TransferAtCashFlowCreated
{
    private $cashFlowRepo;
    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CashFlowRepo $cashFlowRepo, array $data)
    {
        $this->cashFlowRepo = $cashFlowRepo;
        $this->data = $data;
    }

    public function getCashFlowRepo(): CashFlowRepo
    {
        return $this->cashFlowRepo;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
