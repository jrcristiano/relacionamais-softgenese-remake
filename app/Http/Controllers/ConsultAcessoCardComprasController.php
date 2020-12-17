<?php

namespace App\Http\Controllers;

use App\Services\AcessoCardShoppingService;
use App\Services\BaseAcessoCardsCompraService;
use App\Services\HistoryAcessoCardService;
use Illuminate\Http\Request;

class ConsultAcessoCardComprasController extends Controller
{
    private $historyAcessoCardService;
    private $baseAcessoCardsCompraService;
    private $acessoCardShoppingService;

    public function __construct(HistoryAcessoCardService $historyAcessoCardService, BaseAcessoCardsCompraService $baseAcessoCardsCompraService, AcessoCardShoppingService $acessoCardShoppingService)
    {
        $this->historyAcessoCardService = $historyAcessoCardService;
        $this->baseAcessoCardsCompraService = $baseAcessoCardsCompraService;
        $this->acessoCardShoppingService = $acessoCardShoppingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $awardeds = $this->acessoCardShoppingService->getAwardedsByAllAwards($request);
        return view('consult-acesso-cards-compras.index', compact('awardeds'));
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
        $acessoCardShoppings = $this->acessoCardShoppingService->findInfoAcessoCard($document);
        return view('consult-acesso-cards-compras.show', compact('acessoCardShoppings'));
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
