<?php

namespace App\Http\Controllers;

use App\HistoryAcessoCard;
use App\Repositories\AwardRepository as AwardRepo;
use App\Repositories\ConsultAwardedRepository as ConsultAwardedRepo;
use App\Services\HistoryAcessoCardService;
use Illuminate\Http\Request;

class ConsultAwardedController extends Controller
{
    private $consultAwardedRepo;
    private $historyAcessoCardService;

    public function __construct(ConsultAwardedRepo $consultAwardedRepo, HistoryAcessoCardService $historyAcessoCardService)
    {
        $this->consultAwardedRepo = $consultAwardedRepo;
        $this->historyAcessoCardService = $historyAcessoCardService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $awardeds = $this->historyAcessoCardService->getAwardedsByAllAwards();
        return view('consult-awardeds.index', compact('awardeds'));
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
