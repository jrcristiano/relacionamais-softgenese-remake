<?php

namespace App\Http\Controllers;

use App\Helpers\Text;
use App\Services\CallCenterService;
use App\Http\Requests\CallCenterRequest as Request;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;

class CallCenterController extends Controller
{
    private $callCenterService;
    private $acessoCardService;
    private $baseAcessoCardsCompletoService;

    public function __construct(CallCenterService $callCenterService, AcessoCardService $acessoCardService, BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->callCenterService = $callCenterService;
        $this->acessoCardService = $acessoCardService;
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
        $document = request()->get('document');

        $previousCard = $this->baseAcessoCardsCompletoService->firstBaseAcessoCardInativeByDocument($document, $id) ?? null;
        $currencyCard = $this->baseAcessoCardsCompletoService->firstBaseAcessoCardActiveByDocument($document, $id) ?? null;

        return view('call-center.show', compact('callCenter', 'previousCard', 'currencyCard', 'id'));
    }

    public function create()
    {
        $acessoCards = $this->acessoCardService->all();
        $awardedHasCards = $this->baseAcessoCardsCompletoService->getBaseAcessoCardActiveByDocument(request('document'));
        return view('call-center.create', compact('acessoCards', 'awardedHasCards'));
    }

    public function store(Request $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['call_center_phone'] = Text::cleanDocument($data['call_center_phone']);
        $data['call_center_status'] = 1;

        $this->callCenterService->save($data);
        return redirect()->route('admin.operational.call-center');
    }

    public function edit($id)
    {
        $callCenter = $this->callCenterService->firstCallCenter($id);
        $document = request()->get('document');
        $awardedHasCards = $this->baseAcessoCardsCompletoService->getBaseAcessoCardActiveByDocument($document);

        $previousCard = $this->baseAcessoCardsCompletoService->firstBaseAcessoCardInativeByDocument($document, $id) ?? null;
        $currencyCard = $this->baseAcessoCardsCompletoService->firstBaseAcessoCardActiveByDocument($document, $id) ?? null;

        $acessoCards = $this->acessoCardService->all();
        return view('call-center.edit', compact('callCenter', 'acessoCards', 'awardedHasCards', 'id', 'currencyCard', 'previousCard'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['call_center_phone'] = Text::cleanDocument($data['call_center_phone']);

        $this->callCenterService->update($data, 'id', $id);
        return redirect()->route('admin.operational.call-center');
    }
}
