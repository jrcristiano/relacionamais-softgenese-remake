<?php

namespace App\Http\Controllers;

use App\Repositories\AwardRepository;
use App\Services\BaseAcessoCardsCompletoService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    private $awardRepo, $baseAcessoCardsCompletoService;

    public function __construct(AwardRepository $awardRepo, BaseAcessoCardsCompletoService $baseAcessoCardsCompletoService)
    {
        $this->awardRepo = $awardRepo;
        $this->baseAcessoCardsCompletoService = $baseAcessoCardsCompletoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $awardType = $request->tipo_premiacao;
        $awards = $this->awardRepo->getShipmentsbyPaginate(500, $awardType);
        $alerts = $this->awardRepo->getAlerts();
        $hasNotGenerateds = $this->baseAcessoCardsCompletoService->getLikedAndUngenerateCards();
        // dd($hasNotGenerateds);
        // dd($alerts);
        return view('shipments.index', compact('awards', 'alerts', 'hasNotGenerateds'));
    }
}
