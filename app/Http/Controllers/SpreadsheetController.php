<?php

namespace App\Http\Controllers;

use App\Events\Awards\UpdateAwardedValueAfterRemoveAwarded;
use App\Http\Requests\SpreadsheetRequest;
use App\Repositories\AwardRepository as AwardRepo;
use App\Repositories\SpreadsheetRepository as SpreadsheetRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpreadsheetController extends Controller
{
    private $spreadsheetRepo;
    private $awardRepo;

    public function __construct(SpreadsheetRepo $spreadsheetRepo, AwardRepo $awardRepo)
    {
        $this->spreadsheetRepo = $spreadsheetRepo;
        $this->awardRepo = $awardRepo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $awardId = $request->get('premiado_id');
        $spreadsheet = $this->spreadsheetRepo->getData($id, $awardId);
        return view('spreadsheets.edit', compact('spreadsheet', 'awardId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SpreadsheetRequest $request, $id)
    {
        $data = $request->only(array_keys($request->rules()));
        $this->spreadsheetRepo->save($data, $id);

        $spreadsheetTotal = (float) \App\Spreadsheet::select(DB::raw('sum(spreadsheet_value) as spreadsheet_total'))
            ->where('spreadsheet_award_id', $request->spreadsheet_award_id)
            ->whereNull('spreadsheet_chargeback')
            ->first()
            ->spreadsheet_total;

        \App\Award::where('id', $request->spreadsheet_award_id)
            ->update([
                'awarded_value' => $spreadsheetTotal
            ]);

        return redirect()->route('admin.home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new UpdateAwardedValueAfterRemoveAwarded($this->spreadsheetRepo, $this->awardRepo, $id));
        $this->spreadsheetRepo->delete($id);

        return redirect()->route('admin.register.awardeds.show', [
            'id' => \Request::get('premiado_id'),
            'pedido_id' => \Request::get('pedido_id')
        ])
        ->with('message', 'Premiação removida com sucesso!');
    }
}
