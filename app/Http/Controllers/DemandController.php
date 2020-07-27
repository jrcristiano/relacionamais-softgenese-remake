<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandRequest;
use App\Repositories\{AwardRepository as AwardRepo, ClientRepository as ClientRepo,
    DemandRepository as DemandRepo,
};

class DemandController extends Controller
{
    private $demandRepo;
    private $clientRepo;
    private $awardRepo;

    public function __construct(DemandRepo $demandRepo, ClientRepo $clientRepo, AwardRepo $awardRepo)
    {
        $this->demandRepo = $demandRepo;
        $this->clientRepo = $clientRepo;
        $this->awardRepo = $awardRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $demands = $this->demandRepo->getDemandsByPaginate(50);
        return view('demands.index', compact('demands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = $this->clientRepo->getCompanyWithCnpj();
        return view('demands.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DemandRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['demand_prize_amount'] = toReal($data['demand_prize_amount']);
        $data['demand_other_value'] = toReal($data['demand_other_value']);

        $this->demandRepo->saveDemand($data);

        return redirect()->route('admin.home')
            ->with('message', 'Pedido cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $demand = $this->demandRepo->showFirstDemand($id);
        $awards = $this->awardRepo->getAwardsWithColumnShipmentGenerated($id);
        $notes = $this->demandRepo->getDataDemandsNotesBanksByPaginate(100, $id);
        $sale = $this->demandRepo->getSale();
        $spreadsheetTotal = $this->demandRepo->getSumSpreasheetValue($id);

        return view('demands.show', compact('demand', 'awards', 'notes', 'sale', 'spreadsheetTotal', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $demand = $this->demandRepo->find($id);
        $clients = $this->clientRepo->getCompanyWithCnpj();
        return view('demands.edit', compact('demand', 'clients', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DemandRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['demand_prize_amount'] = toReal($data['demand_prize_amount']);
        $data['demand_other_value'] = toReal($data['demand_other_value']);

        $this->demandRepo->saveDemand($data, $id);

        return redirect()->route('admin.home')
            ->with('message', 'Pedido atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->demandRepo->removeNotesSpreadsheetsAwardsAndDemands($id);
        return redirect()->route('admin.home')
            ->with('message', 'Pedido removido com sucesso!');
    }
}
