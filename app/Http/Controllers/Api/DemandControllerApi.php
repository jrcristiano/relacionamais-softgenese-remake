<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;

class DemandControllerApi extends Controller
{
    private $clientRepo;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepo = $clientRepo;
    }

    public function show($awardValue, $cnpj)
    {
        $calculation = $this->clientRepo->getCalculation($awardValue, $cnpj);
        return response()->json($calculation);
    }
}
