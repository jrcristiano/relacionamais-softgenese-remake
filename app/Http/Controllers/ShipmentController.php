<?php

namespace App\Http\Controllers;

use App\Repositories\AwardRepository;

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
    public function index()
    {
        $awards = $this->awardRepo->getShipmentsbyPaginate(50);
        return view('shipments.index', compact('awards'));
    }
}
