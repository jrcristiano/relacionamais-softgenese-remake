<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'select_field' => 'required|min:1',
            'note_receipt_award_real_value' => 'nullable',
            'note_receipt_taxable_real_value' => 'nullable',
            'note_receipt_account_id' => 'required|min:1',
            'note_receipt_date' => 'required|date',
            'note_receipt_id' => 'required|min:1',
            'note_receipt_other_value' => 'nullable',
            'note_receipt_note' => 'nullable',
        ];
    }
}
