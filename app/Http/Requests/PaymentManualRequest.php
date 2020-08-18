<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentManualRequest extends FormRequest
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
            'awarded_value' => 'required|string|min:1',
            'awarded_bank_id' => 'required|string|min:1',
            'awarded_status' => 'required|min:1|in:1,4',
            'awarded_date_payment_manual' => 'required|date',
            'awarded_type' => 'required|min:1'
        ];
    }
}
