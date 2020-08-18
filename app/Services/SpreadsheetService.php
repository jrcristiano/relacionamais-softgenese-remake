<?php

namespace App\Services;

use App\Repositories\SpreadsheetRepository as SpreadsheetRepo;

class SpreadsheetService extends Service
{
    protected $service;

    public function __construct(SpreadsheetRepo $repository)
    {
        $this->service = $repository;
    }

    public function storeShipment($fileName, $demandId, $id)
    {
        return $this->service->storeShipment($fileName, $demandId, $id);
    }
}

