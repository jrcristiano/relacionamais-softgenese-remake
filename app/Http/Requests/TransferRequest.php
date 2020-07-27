<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'transfer_account_credit' => 'required|numeric|min:1',
            'transfer_account_debit' => 'required|numeric|min:1',
            'transfer_value' => 'required|string|min:1',
            'transfer_type' => 'required|numeric|min:1|in:1,2',
            'transfer_date' => 'required|date'
        ];
    }
}
