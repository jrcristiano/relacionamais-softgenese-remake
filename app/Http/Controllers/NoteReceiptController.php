<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteReceiptRequest;
use App\NoteReceipt;
use App\Repositories\CashFlowRepository as CashRepo;
use App\Repositories\NoteReceiptRepository as NoteReceiptRepo;
use App\Repositories\NoteRepository as NoteRepo;

class NoteReceiptController extends Controller
{
    private $noteReceiptRepo;
    private $noteRepo;
    private $cashRepo;

    public function __construct(NoteReceiptRepo $noteReceiptRepo, NoteRepo $noteRepo, CashRepo $cashRepo)
    {
        $this->noteReceiptRepo = $noteReceiptRepo;
        $this->noteRepo = $noteRepo;
        $this->cashRepo = $cashRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = $this->noteRepo->getBanks();
        $noteId = \Request::get('nota_id');
        return view('note-receipts.create', compact('banks', 'noteId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteReceiptRequest $request)
    {
        $demandId = \Request::get('pedido_id');
        $noteId = \Request::get('nota_id');

        $data = $request->only(array_keys($request->rules()));
        $data['note_receipt_demand_id'] = $demandId;

        $patrimony = $data['note_receipt_award_real_value'];
        $awardValue = $data['note_receipt_taxable_real_value'];
        $otherValues = $data['note_receipt_other_value'];

        if ($patrimony != 0 && $awardValue != 0 && $otherValues != 0) {
            return redirect()->back();
        }

        $noteReceipt = $this->noteReceiptRepo->save($data);

        if (isset($noteReceipt->id)) {
            $cashData = [];
            $cashData['flow_movement_date'] = $data['note_receipt_date'];
            $cashData['flow_bank_id'] = $data['note_receipt_account_id'];
            $cashData['flow_receive_id'] = $noteReceipt->id;
            $cashData['flow_payment_date'] = $data['note_receipt_date'];
            $cashData['flow_demand_id'] = $demandId;

            $this->cashRepo->save($cashData);
        }

        return redirect()->route('admin.financial.notes.edit', [ 'id' => $noteId, 'pedido_id' => $demandId ])
            ->with('message', 'Recebimento cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NoteReceipt  $noteReceipt
     * @return \Illuminate\Http\Response
     */
    public function show(NoteReceipt $noteReceipt)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NoteReceipt  $noteReceipt
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $noteReceipt = $this->noteReceiptRepo->find($id);
        $banks = $this->noteRepo->getBanks();
        return view('note-receipts.edit', compact('noteReceipt', 'banks', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NoteReceipt  $noteReceipt
     * @return \Illuminate\Http\Response
     */
    public function update(NoteReceiptRequest $request, $id)
    {
        $demandId = \Request::get('pedido_id');
        $noteId = \Request::get('nota_id');

        $data = $request->only(array_keys($request->rules()));
        $data['note_receipt_demand_id'] = $demandId;

        $patrimony = $data['note_receipt_award_real_value'];
        $awardValue = $data['note_receipt_taxable_real_value'];
        $otherValues = $data['note_receipt_other_value'];

        if ($patrimony != 0 && $awardValue != 0 && $otherValues != 0) {
            return redirect()->back();
        }

        $this->noteReceiptRepo->save($data, $id);

        if (isset($id)) {
            $cashData = [];
            $cashData['flow_movement_date'] = $data['note_receipt_date'];
            $cashData['flow_bank_id'] = $data['note_receipt_account_id'];
            $cashData['flow_receive_id'] = $id;
            $cashData['flow_receive_payment_date'] = $data['note_receipt_date'];
            $cashData['flow_demand_id'] = $demandId;

            $this->cashRepo->updateWhereFlowReceiveId($cashData, $id);
        }

        return redirect()->route('admin.financial.notes.edit', [ 'id' => $noteId, 'pedido_id' => $demandId])
            ->with('message', 'Recebimento editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NoteReceipt  $noteReceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $demandId = \Request::get('pedido_id');
        $noteId = \Request::get('nota_id');

        $this->noteReceiptRepo->delete($id);
        $this->cashRepo->removeWhereFlowReceiveId($id);
        return redirect()->back()
            ->with('message', 'Recebimento removido com sucesso!');
    }
}
