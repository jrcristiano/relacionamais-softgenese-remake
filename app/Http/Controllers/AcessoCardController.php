<?php

namespace App\Http\Controllers;

use App\Facades\AwardTypes\AcessoCard;
use App\Facades\UploadAward;
use App\Repositories\AwardRepository as AwardRepo;
use App\Http\Requests\AcessoCardRequest as Request;
use App\Services\AcessoCardService;

class AcessoCardController extends Controller
{
    private $awardRepo;
    private $acessoCardService;

    public function __construct(AwardRepo $awardRepo, AcessoCardService $acessoCardService)
    {
        $this->awardRepo = $awardRepo;
        $this->acessoCardService = $acessoCardService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('acesso-cards.create');
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
        $data['awarded_type'] = 1;
        $data['awarded_status'] = 3;
        $data['awarded_bank_id'] = 1;
        $data['awarded_card_type'] = 1;
        $data['awarded_demand_id'] = $request->get('pedido_id');

        $upload = new UploadAward($this->awardRepo, $this->acessoCardService);
        $hasErrors = $upload->storeAward($request, $data, AcessoCard::class);

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
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $acessoCards = $this->acessoCardService->getAcessoCardsWhereAwarded($id);
        return view('acesso-cards.show', compact('acessoCards', 'id'));
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
        $data = $request->only(array_keys($request->rules()));
        $this->awardRepo->save($data, $id);
        return redirect()->route('admin.show', ['id' => $request->get('pedido_id'), 'premiacao' => 1])
            ->with('message', 'Premiação cadastrada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->acessoCardService->delete($id);
        return redirect()
            ->route('admin.register.acesso-cards.show', [
                'id' => \Request::get('card_id'),
                'pedido_id' => \Request::get('pedido_id')]
            )
            ->with('message', 'Premiação removida com sucesso!');
    }
}
