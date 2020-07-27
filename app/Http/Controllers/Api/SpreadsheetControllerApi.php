<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpreadsheetControllerApi extends Controller
{
    public function update(Request $request, $id)
    {
        if ($request->get('spreadsheet_chargeback')) {
            $data = $this->validate($request, [
                'spreadsheet_chargeback' => 'required|boolean',
            ]);

            $lessSpreadSheetValue = (float) \App\Spreadsheet::select('spreadsheet_value as less_spreadsheet_value')
                ->where('id', $id)
                ->whereNull('spreadsheet_chargeback')
                ->first()
                ->less_spreadsheet_value;

            $spreadsheetTotal = (float) \App\Spreadsheet::select(DB::raw('sum(spreadsheet_value) as spreadsheet_total'))
                ->where('spreadsheet_award_id', $request->award_id)
                ->whereNull('spreadsheet_chargeback')
                ->first()
                ->spreadsheet_total;

            $total = (float) $spreadsheetTotal - $lessSpreadSheetValue;

            \App\Award::where('id', $request->award_id)
                ->update([
                    'awarded_value' => $total
                ]);
            \App\Spreadsheet::find($id)->fill($data)->save();
        }

        return redirect()->back();
    }
}
