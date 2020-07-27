<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandRequest;
use App\Repositories\DemandRepository as DemandRepo;
use App\Repositories\NoteRepository as NoteRepo;
use App\Http\Requests\NoteRequest;
use App\Repositories\BillRepository as BillRepo;
use App\Repositories\CashFlowRepository as CashFlowRepo;
use App\Repositories\NoteReceiptRepository as NoteReceiptRepo;
use App\Repositories\ReceiveRepository as ReceiveRepo;

class NoteController extends Controller
{
    protected $noteRepo;
    protected $demandRepo;
    protected $cashRepo;
    protected $receiveRepo;
    protected $billRepo;

    public function __construct(NoteRepo $noteRepo, DemandRepo $demandRepo, CashFlowRepo $cashRepo, ReceiveRepo $receiveRepo, NoteReceiptRepo $noteReceiptRepo, BillRepo $billRepo)
    {
        $this->noteRepo = $noteRepo;
        $this->demandRepo = $demandRepo;
        $this->cashRepo = $cashRepo;
        $this->receiveRepo = $receiveRepo;
        $this->noteReceiptRepo = $noteReceiptRepo;
        $this->billRepo = $billRepo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $data = $request->only(array_keys($request->rules()));
        $data['note_number'] = str_pad($request->note_number, 4, '0', STR_PAD_LEFT);

        try {
            $this->noteRepo->save($data);
            return redirect()->route('admin.home')
                ->with('message', 'Nota fiscal cadastrada com sucesso!');

        } catch (\PDOException $e) {
            return redirect()->back()->withErrors(['número de nota fiscal já cadastrado em nossos sistemas.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nfe  $nfe
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nfe  $nfe
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $requestDemandId = \Request::get('pedido_id');
        $noteReceiptId = \Request::get('recebimento_id');
        $note = $this->noteRepo->findNotesWhereNoteDemandId($requestDemandId);
        $noteReceipts = $this->noteReceiptRepo->getNoteReceiptsByPaginate(50, $id);
        $noteReceipt = $this->noteReceiptRepo->find($noteReceiptId);

        $patrimony = $this->billRepo->getPatrimony($id) + $this->billRepo->getOtherValues($id);
        $award = $this->billRepo->getAward($id);
        $sale = $patrimony + $award;

        return view('notes.edit', compact('note', 'noteReceipts', 'noteReceipt', 'patrimony', 'award', 'sale', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $noteRequest, DemandRequest $demandRequest, $id)
    {
        $noteData = $noteRequest->only(array_keys($noteRequest->rules()));
        if ($noteData['note_status'] != 2) {
            $noteData['note_receipt_date'] = null;
        }
        $demandData = $demandRequest->only(array_keys($demandRequest->rules()));

        // dd($demandData);

        $this->demandRepo->save($demandData, $demandData['note_demand_id']);

        $data = [];
        $data['receive_award_real_value'] = $demandData['demand_prize_amount'];
        $data['receive_taxable_real_value'] = $demandData['demand_taxable_amount'];
        $data['receive_status'] = $noteData['note_status'];
        $data['receive_demand_id'] = $demandRequest->note_demand_id;
        $data['receive_date_receipt'] = now();

        $flowData = [];
        if ($noteData['note_status'] == 2) {
            $flowData['receive_payment_date'] = date('Y-m-d');
        }

        $this->receiveRepo->save($data);
        $this->noteRepo->save($noteData, $id);

        return redirect()->route('admin.financial.receives')
            ->with('message', 'Nota fiscal editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nfe  $nfe
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->noteRepo->delete($id);
        return redirect()->route('admin.home')
            ->with('message', 'Nota fiscal removida com sucesso!');
    }
}
