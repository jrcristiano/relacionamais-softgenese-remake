<?php

namespace App\Http\Controllers;

use App\Services\HistoryAcessoCardService;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use Illuminate\Http\Request;

class ConsultAcessoCardCompletoController extends Controller
{
    private $historyAcessoCardService;
    private $baseAcessoCardsCompletoService;
    private $acessoCardService;

    public function __construct(HistoryAcessoCardService $historyAcessoCardService, AcessoCardService $acessoCardService, BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->historyAcessoCardService = $historyAcessoCardService;
        $this->acessoCardService = $acessoCardService;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $awardeds = $this->acessoCardService->getAwardedsByAllAwards($request);
        $filters = $this->historyAcessoCardService->getDataForFilters();
        return view('acesso-cards-completo.index', compact('awardeds', 'filters'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($document)
    {
        $acessoCards = $this->acessoCardService->findInfoAcessoCard($document);
        return view('acesso-cards-completo.show', compact('acessoCards'));
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
    public function update(Request $request, $proxy)
    {
        $callCenterId = $request->get('cancel_call_center_id');
        $baseAcessoCard = $this->baseAcessoCardsCompletoService->findByProxy($proxy);

        \App\CallCenter::where('id', $callCenterId)
            ->update([
                'call_center_status' => $request->get('cancel_call_center_status')
            ]);

        \App\BaseAcessoCardCompletoOrder::create([
            'previous_card_id' => $baseAcessoCard->id,
            'call_center_id' => $callCenterId
        ]);

        $this->baseAcessoCardsCompletoService->saveByParam([
            'base_acesso_card_status' => 2
        ], 'base_acesso_card_proxy', $proxy);

        return redirect()->back();
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
