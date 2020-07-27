<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'receive_award_real_value' => 'required|numeric|min:1',
            'receive_taxable_real_value' => 'required|numeric|min:1',
            'receive_date_receipt' => 'required|date|min:1',
            'receive_status' => 'required|min:1'
        ];
    }
}
