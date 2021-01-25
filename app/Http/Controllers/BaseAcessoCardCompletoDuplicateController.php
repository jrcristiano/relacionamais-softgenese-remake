<?php

namespace App\Http\Controllers;

use App\AcessoCard;
use App\HistoryAcessoCard;
use App\Repositories\HistoryAcessoCardRepository;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use App\Services\HistoryAcessoCardService;
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

    public function update(Request $request)
    {
        $data['prize_amount'] = toReal($request->get('prize_amount'));

        $callCenterId = request('duplicate_call_center_id');

        \App\CallCenter::where('id', $callCenterId)
            ->update([
                'call_center_status' => $request->get('duplicate_call_center_status')
            ]);

        $baseAcessoCardCompleto = $this->baseAcessoCardCompletoService->findActiveCardByDocument(request('document'));

        $acessoCard = $this->acessoCardService->findByDocument(request('document'));

        $demand = \App\Demand::find($acessoCard->acesso_card_demand_id);

        $this->baseAcessoCardCompletoService->save([
            'base_acesso_card_status' => 2
        ], $baseAcessoCardCompleto->id);

        $firstUnlikedCard = $this->baseAcessoCardCompletoService->firstUnlikedBaseCardCompleto();

        \App\BaseAcessoCardCompletoOrder::create([
            'previous_card_id' => $baseAcessoCardCompleto->id,
            'currency_card_id' => $firstUnlikedCard->id,
            'call_center_id' => $request->duplicate_call_center_id
        ]);

        $this->baseAcessoCardCompletoService->save([
            'base_acesso_card_name' => $baseAcessoCardCompleto->base_acesso_card_name,
            'base_acesso_card_cpf' => $baseAcessoCardCompleto->base_acesso_card_cpf,
            'base_acesso_card_status' => 1
        ], $firstUnlikedCard->id);

        $activeAcessoCard = $this->acessoCardService->save([
            'acesso_card_name' => $acessoCard->acesso_card_name,
            'acesso_card_document' => $acessoCard->acesso_card_document,
            'acesso_card_value' => $acessoCard->acesso_card_value,
            'acesso_card_number' => $firstUnlikedCard->base_acesso_card_number,
            'acesso_card_proxy' => $firstUnlikedCard->base_acesso_card_proxy,
            'acesso_card_already_exists' => $acessoCard->acesso_card_already_exists,
            'acesso_card_spreadsheet_line' => 1,
            'acesso_card_award_id' => $acessoCard->acesso_card_award_id,
            'acesso_card_generated' => $acessoCard->acceso_card_generated,
            'acesso_card_chargeback' => $acessoCard->acesso_card_chargeback,
            'acesso_card_demand_id' => $acessoCard->acesso_card_demand_id,
        ]);

        $historyService = new HistoryAcessoCardService(new HistoryAcessoCardRepository(new HistoryAcessoCard()));
        $historyService->save([
            'history_base_id' => $firstUnlikedCard->id,
            'history_acesso_card_id' => $activeAcessoCard->id
        ]);

        return redirect()->back();
    }
}
