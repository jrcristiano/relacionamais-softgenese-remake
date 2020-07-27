<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpreadsheetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spreadsheet_name' => 'required|string|min:3',
            'spreadsheet_document' => 'required|string|min:11',
            'spreadsheet_value' => 'required|string|min:1',
            'spreadsheet_bank' => 'required|string|min:2',
            'spreadsheet_agency' => 'required|string|min:3',
            'spreadsheet_account' => 'required|string|min:3',
            'spreadsheet_account_type' => 'required|string|min:1',
            'spreadsheet_award_id' => 'required|numeric|min:1',
            'spreadsheet_demand_id' => 'required|min:1',
        ];
    }
}
