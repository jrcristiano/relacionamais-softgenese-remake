<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use App\Services\HistoryAcessoCardService;
use Illuminate\Http\Request;

class BaseAcessoCardCompletoController extends Controller
{
    private $historyAcessoCardService;
    private $baseAcessoCardsCompletoService;
    private $acessoCardService;

    public function __construct(HistoryAcessoCardService $historyAcessoCardService,
        BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService,
        AcessoCardService $acessoCardService)
    {
        $this->historyAcessoCardService = $historyAcessoCardService;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
        $this->acessoCardService = $acessoCardService;
    }

    public function update(Request $request, $id)
    {
        $findBaseAcessoCards = $this->baseAcessoCardsCompletoService->getUnlikedBaseCardCompleto();

        foreach ($findBaseAcessoCards as $findBaseAcessoCard) {
            $this->baseAcessoCardsCompletoService->save([
                'base_acesso_card_generated' => 1,
            ], $findBaseAcessoCard->id);
        }
    }
}
