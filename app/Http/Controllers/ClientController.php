<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Repositories\ClientRepository as ClientRepo;
use App\Repositories\ManagerRepository as ManagerRepo;

class ClientController extends Controller
{
    private $clientRepo;
    private $managerRepo;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(ClientRepo $clientRepo, ManagerRepo $managerRepo)
     {
        $this->clientRepo = $clientRepo;
        $this->managerRepo = $managerRepo;
     }

    public function index()
    {
        $clients = $this->clientRepo->getClientsByPaginate(50);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $managers = $this->managerRepo->getManagerNameAndId();
        return view('clients.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));

        try {
            $this->clientRepo->save($data);
            return redirect()->route('admin.register.clients')
                ->with('message', 'cliente cadastrado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['CNPJ já cadastrado em nosso sistema.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->clientRepo->find($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = $this->clientRepo->find($id);
        $managers = $this->managerRepo->getManagerNameAndId();
        return view('clients.edit', compact('client', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));

        try {
            $this->clientRepo->save($data, $id);
            return redirect()->route('admin.register.clients')
                ->with('message', 'cliente editado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['CNPJ já cadastrado em nosso sistema.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->clientRepo->delete($id);
        return redirect()->route('admin.register.clients')
            ->with('message', 'cliente removido com sucesso!');
    }
}
