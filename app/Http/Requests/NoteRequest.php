<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $noteId = \Request::get('id');
        return [
            'note_number' => "max:254|unique:notes,note_number,{$noteId}",
            'note_status' => 'required|numeric|min:1|in:1,2,3',
            'note_due_date' => 'required|date',
            'note_receipt_date' => 'nullable',
            'note_account_receipt_id' => 'nullable',
            'note_demand_id' => 'required|numeric|min:1',
            'note_created_at' => 'required|date'
        ];
    }
}
