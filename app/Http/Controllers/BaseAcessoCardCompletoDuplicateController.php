<?php

namespace App\Http\Controllers;

use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use Illuminate\Http\Request;

class BaseAcessoCardCompletoDuplicateController extends Controller
{
    private $baseAcessoCardCompletoService;
    private $acessoCardService;

    public function __construct(BaseAcessoCardsCompletoService $baseAcessoCardCompletoService, AcessoCardService $acessoCardService)
    {
        $this->baseAcessoCardCompletoService = $baseAcessoCardCompletoService;
        $this->acessoCardService = $acessoCardService;
    }

    public function update(Request $request, $proxy)
    {
        $data['prize_amount'] = toReal($request->get('prize_amount'));

        $callCenterId = $request->get('duplicate_call_center_id');

        \App\CallCenter::where('id', $callCenterId)
            ->update([
                'call_center_status' => $request->get('duplicate_call_center_status')
            ]);

        $baseAcessoCardCompleto = $this->baseAcessoCardCompletoService->findByProxy($proxy);

        $acessoCard = $this->acessoCardService->findByProxy($proxy);
        $demand = \App\Demand::find($acessoCard->acesso_card_demand_id);

        \App\Demand::create([
            'demand_client_cnpj' => $demand->demand_client_cnpj,
            'demand_client_name' => $demand->demand_client_name,
            'demand_prize_amount' => $data['prize_amount'],
            'demand_taxable_amount' => 0,
            'demand_other_value' => 0
        ]);

        $this->baseAcessoCardCompletoService->save([
            'base_acesso_card_status' => 2
        ], $baseAcessoCardCompleto->id);

        $firstUnlikedCard = $this->baseAcessoCardCompletoService->firstUnlikedBaseCardCompleto();

        \App\BaseAcessoCardCompletoOrder::create([
            'previous_card_id' => $baseAcessoCardCompleto->id,
            'currency_card_id' => $firstUnlikedCard->id,
        ]);

        $this->baseAcessoCardCompletoService->save([
            'base_acesso_card_name' => $baseAcessoCardCompleto->base_acesso_card_name,
            'base_acesso_card_cpf' => $baseAcessoCardCompleto->base_acesso_card_cpf,
            'base_acesso_card_status' => 1
        ], $firstUnlikedCard->id);

        return redirect()->back();
    }
}
