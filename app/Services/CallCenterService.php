<?php

namespace App\Services;

use App\Repositories\CallCenterRepository;

class CallCenterService extends Service
{
    protected $service;

    public function __construct(CallCenterRepository $callCenterRepository)
    {
        $this->service = $callCenterRepository;
    }

    public function getCallCentersByPaginate($perPage = 500)
    {
        return $this->service->getCallCentersByPaginate($perPage);
    }

    public function firstCallCenter($id)
    {
        return $this->service->firstCallCenter($id);
    }
}
