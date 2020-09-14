<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BaseAcessoCardsCompletoService;
use App\Services\HistoryAcessoCardService;
use Illuminate\Http\Request;

class BaseAcessoCardCompletoController extends Controller
{
    private $historyAcessoCardService;
    private $baseAcessoCardsCompletoService;

    public function __construct(HistoryAcessoCardService $historyAcessoCardService, BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->historyAcessoCardService = $historyAcessoCardService;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
    }

    public function update(Request $request, $id)
    {
        $histories = $this->historyAcessoCardService->getInfoBaseAcessoCardsNotGeneratedAndAcessoCardsByAwardId($id);

        foreach ($histories as $history) {
            $this->baseAcessoCardsCompletoService->saveByDocument([
                'base_acesso_card_generated' => 1
            ], $history->acesso_card_document);
        }

        return redirect()->back()->with('message', 'Premiação cadastrada com sucesso!');
    }
}
