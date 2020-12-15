<?php

namespace App\Http\Controllers;

use App\Services\AcessoCardShoppingService;
use App\Services\AwardService;
use App\Services\BaseAcessoCardsCompraService;
use Illuminate\Http\Request;

class PartAcessoCardShoppingController extends Controller
{
    private $acessoCardShoppingService;
    private $awardService;

    public function __construct(AcessoCardShoppingService $acessoCardShoppingService, AwardService $awardService)
    {
        $this->acessoCardShoppingService = $acessoCardShoppingService;
        $this->awardService = $awardService;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'acesso_card_shopping_id' => 'required|min:1'
        ]);

        $acessoCardAward = $this->awardService->find($data['acesso_card_shopping_id']);

        $award = $this->awardService->save([
            'awarded_value' => $acessoCardAward->awarded_value,
            'awarded_type' => $acessoCardAward->awarded_type,
            'awarded_status' => $acessoCardAward->awarded_status,
            'awarded_upload_table' => $acessoCardAward->awarded_upload_table,
            'awarded_demand_id' => $acessoCardAward->awarded_demand_id,
            'awarded_bank_id' => $acessoCardAward->awarded_bank_id,
            'award_already_parted' => 1,
            'awarded_type_card' => 2,
        ]);

        $acessoCards = $this->acessoCardShoppingService->getAllNewsAcessoCardsWhereAcessoCardAwardedId($data['acesso_card_shopping_id']);

        $acessoCardValue = 0;
        foreach ($acessoCards as $acessoCard) {
            $acessoCardValue += $acessoCard->acesso_card_shopping_value;
        }

        $acessoCards->each(function () use ($award, $acessoCardAward, $acessoCardValue) {
            $awardedValue = $award->awarded_value;

            $this->awardService->update([
                'awarded_value' => $awardedValue,
                'award_already_parted' => 1,
                'awarded_type_card' => 2
            ], 'id', $acessoCardAward->id);
        });

        foreach ($acessoCards as $acessoCard) {
            $this->acessoCardShoppingService->updateAcessoCardsNotExists([
                'acesso_card_shopping_already_exists' => 1,
                'acesso_card_shopping_award_id' => $award->id
            ], 'acesso_card_shopping_award_id', $acessoCard->acesso_card_shopping_award_id);
        }

        $this->awardService->update([
            'awarded_value' => $acessoCardValue,
            'award_already_parted' => 1,
            'awarded_type_card' => 2
        ], 'id', $acessoCardAward->id);

        return redirect()->back();
    }
}
