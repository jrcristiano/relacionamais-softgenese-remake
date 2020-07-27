<?php

namespace App\Http\Controllers;

use App\Repositories\RewardRepository;
use App\Rewarded;
use Illuminate\Http\Request;

class RewardedController extends Controller
{
    private $rewardRepo;

    public function __construct(RewardRepository $rewardRepo)
    {
        $this->rewardRepo = $rewardRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rewards = null;
        return view('rewards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rewarded  $rewarded
     * @return \Illuminate\Http\Response
     */
    public function show(Rewarded $rewarded)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rewarded  $rewarded
     * @return \Illuminate\Http\Response
     */
    public function edit(Rewarded $rewarded)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rewarded  $rewarded
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rewarded $rewarded)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rewarded  $rewarded
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rewarded $rewarded)
    {
        //
    }
}
