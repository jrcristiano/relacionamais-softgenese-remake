<?php

namespace App\Services;

use App\Repositories\AwardRepository;

class AwardService extends Service
{
    protected $service;

    public function __construct(AwardRepository $repository)
    {
        $this->service = $repository;
    }
}
