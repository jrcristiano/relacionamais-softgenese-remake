<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Repositories\BankRepository;

class BankController extends Controller
{
    private $bankRepo;

    public function __construct(BankRepository $repository)
    {
        $this->bankRepo = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = $this->bankRepo->getBanksByPaginate(50);
        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));

        try {
            $this->bankRepo->save($data);
            return redirect()->route('admin.register.banks')
                ->with('message', 'banco cadastrado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['conta já cadastrado em nosso sistema.']);
        }
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
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bank = $this->bankRepo->find($id);
        return view('banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BankRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));

        try {
            $this->bankRepo->save($data, $id);
            return redirect()->route('admin.register.banks')
                ->with('message', 'banco atualizado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['conta já cadastrada em nosso sistema.']);
        }
    }
}
