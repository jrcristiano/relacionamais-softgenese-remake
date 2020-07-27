<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AwardRepository;

class AwardControllerApi extends Controller
{
    private $awardRepo;

    public function __construct(AwardRepository $repository)
    {
        $this->awardRepo = $repository;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'awarded_value' => 'required'
        ]);

        $this->awardRepo->save([]);
        return response()->json([
            'message' => 'Premiação cadastrada com sucesso!',
            'status' => true
        ], 201, ['*']);
    }
}
