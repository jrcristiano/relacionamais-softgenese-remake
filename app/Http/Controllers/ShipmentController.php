<?php

namespace App\Http\Controllers;

use App\Repositories\AwardRepository;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    private $awardRepo;

    public function __construct(AwardRepository $awardRepo)
    {
        $this->awardRepo = $awardRepo;
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
        return view('shipments.index', compact('awards'));
    }
}
