<?php

namespace App\Http\Controllers;

use App\Facades\AwardTypes\AcessoCardShopping;
use App\Facades\UploadAward;
use App\Http\Requests\AcessoCardRequest as Request;
use App\Repositories\AwardRepository;
use App\Services\AcessoCardShoppingService;

class AcessoCardShoppingController extends Controller
{
    private $awardRepo;
    private $acessoCardShoppingService;

    public function __construct(AwardRepository $awardRepo, AcessoCardShoppingService $acessoCardShoppingService)
    {
        $this->awardRepo = $awardRepo;
        $this->acessoCardShoppingService = $acessoCardShoppingService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('acesso-cards-compras.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(array_keys($request->rules()));

        $data['awarded_type'] = 4;
        $data['awarded_status'] = 3;
        $data['awarded_bank_id'] = 3;
        $data['awarded_type_card'] = 2;
        $data['awarded_demand_id'] = $request->get('pedido_id');

        $upload = new UploadAward($this->awardRepo, $this->acessoCardShoppingService);
        $hasErrors = $upload->storeAward($request, $data, AcessoCardShopping::class);

        if (is_array($hasErrors)) {
            return redirect()->back()
                ->with('error', $hasErrors);
        }

        return redirect()->route('admin.show', ['id' => $request->get('pedido_id'), 'premiacao' => 1])
            ->with('message', 'Premiação cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AcessoCardShopping  $acessoCardShopping
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $acessoCards = $this->acessoCardShoppingService->getAcessoCardsWhereAwardedWithChargeback($id);
        return view('acesso-cards-compras.show', compact('acessoCards', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AcessoCardShopping  $acessoCardShopping
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
     * @param  \App\AcessoCardShopping  $acessoCardShopping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['id'] = $id;

        $upload = new UploadAward($this->awardRepo, $this->acessoCardShoppingService);
        $hasErrors = $upload->updateAward($data, AcessoCardShopping::class);

        if (is_array($hasErrors)) {
            return redirect()->back()
                ->with('error', $hasErrors);
        }

        $this->awardRepo->save($data, $id);
        return redirect()->route('admin.show', ['id' => $request->get('pedido_id'), 'premiacao' => 1])
            ->with('message', 'Premiação cadastrada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AcessoCardShopping  $acessoCardShopping
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $acessoCardShoppingAward = $this->acessoCardShoppingService->find($id);

        $acessoCardShoppingAwardValue = (float) $acessoCardShoppingAward->acesso_card_shopping_value;

        $award = $this->awardRepo->find($acessoCardShoppingAward->acesso_card_shopping_award_id);
        $awardValue = (float) $award->awarded_value;

        $this->awardRepo->save([
            'awarded_value' => $awardValue - $acessoCardShoppingAwardValue,
        ], $award->id);

        \App\HistoryAcessoCardCompra::where('history_acesso_card_id', $id)->delete();
        $this->acessoCardShoppingService->delete($id);

        return redirect()
            ->route('admin.register.acesso-cards-shopping.show', [
                'id' => request('card_id'),
                'pedido_id' => request('pedido_id')
            ])
            ->with('message', 'Premiação removida com sucesso!');
    }
}
