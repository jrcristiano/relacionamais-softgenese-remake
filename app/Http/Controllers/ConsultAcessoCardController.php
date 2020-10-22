<?php

namespace App\Http\Controllers;

use App\Services\HistoryAcessoCardService;
use App\Services\AcessoCardService;
use Illuminate\Http\Request;

class ConsultAcessoCardController extends Controller
{
    private $historyAcessoCardService;
    private $acessoCardService;

    public function __construct(HistoryAcessoCardService $historyAcessoCardService, AcessoCardService $acessoCardService)
    {
        $this->historyAcessoCardService = $historyAcessoCardService;
        $this->acessoCardService = $acessoCardService;
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
        return view('consult-awardeds.index', compact('awardeds', 'filters'));
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
    public function show($card)
    {
        $acessoCards = $this->acessoCardService->findInfoAcessoCard($card);
        return view('consult-awardeds.show', compact('acessoCards'));
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
