<?php

namespace App\Http\Controllers;

use App\Services\AcessoCardService;
use App\Services\HistoryAcessoCardService;
use Illuminate\Http\Request;

class ConsultAwardedController extends Controller
{

    private $historyAcessoCardService;


    public function __construct(HistoryAcessoCardService $historyAcessoCardService)
    {
        $this->historyAcessoCardService = $historyAcessoCardService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $awardeds = $this->historyAcessoCardService->getAwardedsByAllAwards($request);
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
