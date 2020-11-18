<?php

namespace App\Http\Controllers;

use App\Services\BaseAcessoCardsCompletoService;
use Illuminate\Http\Request;

class BaseAcessoCardCompletoDuplicateController extends Controller
{
    private $baseAcessoCardCompletoService;

    public function __construct(BaseAcessoCardsCompletoService $baseAcessoCardCompletoService)
    {
        $this->baseAcessoCardCompletoService = $baseAcessoCardCompletoService;
    }

    public function update(Request $request, $proxy)
    {
        $baseAcessoCardCompleto = $this->baseAcessoCardCompletoService->findByProxy($proxy);
        $this->baseAcessoCardCompletoService->save([
            'base_acesso_card_status' => 2
        ], $baseAcessoCardCompleto->id);

        $firstUnlikedCard = $this->baseAcessoCardCompletoService->firstUnlikedBaseCardCompleto();

        $this->baseAcessoCardCompletoService->save([
            'base_acesso_card_name' => $baseAcessoCardCompleto->base_acesso_card_name,
            'base_acesso_card_cpf' => $baseAcessoCardCompleto->base_acesso_card_cpf,
            'base_acesso_card_status' => 1
        ], $firstUnlikedCard->id);

        return redirect()->back()
            ->with('message', "O cartão foi cancelado e uma 2º via foi emitida para {$baseAcessoCardCompleto->base_acesso_card_name}");
    }
}
