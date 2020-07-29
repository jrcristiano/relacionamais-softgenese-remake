<?php

namespace App\Http\Controllers;

use App\Repositories\ReceiveRepository as ReceiveRepo;
use App\Http\Requests\ReceiveRequest;
use App\Repositories\ClientRepository as ClientRepo;
use Illuminate\Http\Request;

class ReceiveController extends Controller
{
    private $receiveRepo;
    private $clientRepo;

    public function __construct(ReceiveRepo $repository, ClientRepo $client)
    {
        $this->receiveRepo = $repository;
        $this->clientRepo = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $clients = $this->clientRepo->all();
        $filter = [
            $request->receive_in,
            $request->receive_until,
            $request->receive_status,
            $request->receive_client
        ];

        $receives = $this->receiveRepo->getDataDemandsNotesBanksByPaginate(100, $filter);
        $awardTotal = $this->receiveRepo->getAwardTotal($filter);
        $patrimonyTotal = $this->receiveRepo->getPatrimonyTotal($filter);
        $otherValueTotal = $this->receiveRepo->getOtherValues($filter);

        $saleTotal = $awardTotal->award_total + $patrimonyTotal->patrimony_total + $otherValueTotal->other_value_total;

        return view('receives.index', compact('receives', 'clients', 'awardTotal', 'patrimonyTotal', 'otherValueTotal', 'saleTotal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $demands = $this->receiveRepo->getDemandToReceiveSelect();
        return view('receives.create', compact('demands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReceiveRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $this->receiveRepo->save($data);

        return redirect()->route('admin.financial.receives')
            ->with('message', 'Conta a receber cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $receive = $this->receiveRepo->find($id);
        return view('receives.show', compact('receive', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $receive = $this->receiveRepo->find($id);
        return view('receives.edit', compact('receive'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function update(ReceiveRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $this->receiveRepo->save($data, $id);

        return redirect()->route('admin.financial.receives')
            ->with('message', 'Conta a receber editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $demandId = \Request::get('pedido_id');
        $this->receiveRepo->deleteNotesAndNoteReceipts($id, $demandId);
        return redirect()->route('admin.financial.receives');
    }
}
