<?php

namespace App\Http\Controllers;

use App\Services\AcessoCardService;
use App\Services\AwardService;
use App\Services\PartAcessoCardService;
use Illuminate\Http\Request;

class PartAcessoCardController extends Controller
{
    private $acessoCardService;
    private $awardService;

    public function __construct(AcessoCardService $acessoCardService, AwardService $awardService)
    {
        $this->acessoCardService = $acessoCardService;
        $this->awardService = $awardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'acesso_card_id' => 'required|min:1'
        ]);

        $acessoCardAward = $this->awardService->find($data['acesso_card_id']);
        $award = $this->awardService->save([
            'awarded_value' => $acessoCardAward->awarded_value,
            'awarded_type' => $acessoCardAward->awarded_type,
            'awarded_status' => $acessoCardAward->awarded_status,
            'awarded_upload_table' => $acessoCardAward->awarded_upload_table,
            'awarded_demand_id' => $acessoCardAward->awarded_demand_id,
            'awarded_bank_id' => $acessoCardAward->awarded_bank_id,
            'award_already_parted' => 1
        ]);

        $acessoCards = $this->acessoCardService->getAllNewsAcessoCardsWhereAcessoCardAwardedId($data['acesso_card_id']);

        $acessoCardValue = 0;
        foreach ($acessoCards as $acessoCard) {
            $acessoCardValue += $acessoCard->acesso_card_value;
        }

        $acessoCards->each(function ($acessoCard) use ($award, $acessoCardAward, $acessoCardValue) {
            $awardedValue = (float) $award->awarded_value - $acessoCardValue;

            $this->awardService->update([
                'awarded_value' => $award->awarded_value - $acessoCardValue,
                'award_already_parted' => 1,
            ], 'id', $acessoCardAward->id);

            $this->acessoCardService->updateAcessoCardsAlreadyExists([
                'acesso_card_award_id' => $award->id
            ], 'id', $acessoCard->id);
        });

        $this->awardService->update([
            'awarded_value' => $acessoCardValue,
            'award_already_parted' => 1,
        ], 'id', $award->id);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
