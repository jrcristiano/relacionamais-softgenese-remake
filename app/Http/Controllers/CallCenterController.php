<?php

namespace App\Http\Controllers;

use App\Helpers\Text;
use App\Services\BaseAcessoCardsCompletoService;
use App\Services\CallCenterService;
use App\Http\Requests\CallCenterRequest as Request;

class CallCenterController extends Controller
{
    private $callCenterService;
    private $baseAcessoCardsCompletoService;

    public function __construct(CallCenterService $callCenterService, BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->callCenterService = $callCenterService;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
    }

    public function index()
    {
        $callCenters = $this->callCenterService->getCallCentersByPaginate();
        return view('call-center.index', compact('callCenters'));
    }

    public function show($id)
    {
        $callCenter = $this->callCenterService->firstCallCenter($id);
        return view('call-center.show', compact('callCenter'));
    }

    public function create()
    {
        $baseAcessoCardsCompletos = $this->baseAcessoCardsCompletoService->getAllActiveCards();
        return view('call-center.create', compact('baseAcessoCardsCompletos'));
    }

    public function store(Request $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['call_center_phone'] = Text::cleanDocument($data['call_center_phone']);

        $this->callCenterService->save($data);
        return redirect()->route('admin.operational.call-center');
    }

    public function edit($id)
    {
        $callCenter = $this->callCenterService->firstCallCenter($id);
        $baseAcessoCardsCompletos = $this->baseAcessoCardsCompletoService->getAllActiveCards();
        return view('call-center.edit', compact('callCenter', 'baseAcessoCardsCompletos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['call_center_phone'] = Text::cleanDocument($data['call_center_phone']);

        $this->callCenterService->save($data, $id);
        return redirect()->route('admin.operational.call-center');
    }
}
