<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagerRequest;
use App\Repositories\ManagerRepository;

class ManagerController extends Controller
{
    private $managerRepo;

    public function __construct(ManagerRepository $repository)
    {
        $this->managerRepo = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $managers = $this->managerRepo->getManagersByPaginate(50);
        return view('managers.index', compact('managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('managers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));
        try {
            $this->managerRepo->save($data);
            return redirect()->route('admin.register.managers')
                ->with('message', 'gerente cadastrado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['Email e/ou CPF já cadastrado(s) em nossos sistemas.']);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manager = $this->managerRepo->find($id);
        return view('managers.edit', compact('manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        try {
            $this->managerRepo->save($data, $id);
            return redirect()->route('admin.register.managers')
                ->with('message', 'gerente editado com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['Email e/ou CPF já cadastrado(s) em nosso sistema.']);
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
        $this->managerRepo->delete($id);
        return redirect()->route('admin.register.managers')
            ->with('message', 'gerente removido com sucesso!');
    }
}
