<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $status = isset($this->id) ? 'required|string|min:1' : 'nullable';
        return [
            'bill_value' => 'required|string|min:1',
            'bill_payday' => 'required|date|min:8',
            'bill_due_date' => 'required|date|min:8',
            'bill_provider_id' => 'required|string',
            'bill_payment_status' => $status,
            'bill_bank_id' => 'required|min:1',
            'bill_note' => 'nullable',
            'bill_description' => 'nullable',
        ];
    }
}
