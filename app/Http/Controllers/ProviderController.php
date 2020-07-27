<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Repositories\ProviderRepository;

class ProviderController extends Controller
{
    private $providerRepo;

    public function __construct(ProviderRepository $repository)
    {
        $this->providerRepo = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = $this->providerRepo->getProvidersByPaginate(50);
        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));
        try {
            $this->providerRepo->save($data);
            return redirect()->route('admin.register.providers')
                ->with('message', 'fornecedor cadastrado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['CPF/CNPJ já cadastrados em nossos sistema.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = $this->providerRepo->find($id);
        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        try {
            $this->providerRepo->save($data, $id);
            return redirect()->route('admin.register.providers')
                ->with('message', 'fornecedor cadastrado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['CPF/CNPJ já cadastrado em nossos sistemas.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->providerRepo->delete($id);
        return redirect()->route('admin.register.providers')
            ->with('message', 'fornecedor removido com sucesso!');
    }
}
