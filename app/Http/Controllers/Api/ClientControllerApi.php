<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;

class ClientControllerApi extends Controller
{
    private $clientRepo;

    public function __construct(ClientRepository $repository)
    {
        $this->clientRepo = $repository;
    }

    public function index()
    {
        return $this->clientRepo->all()
            ->toJson();
    }

    public function show($data)
    {
        return $this->clientRepo->getData($data)
            ->toJson();
    }
}
