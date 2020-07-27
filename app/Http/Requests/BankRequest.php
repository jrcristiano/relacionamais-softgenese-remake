<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_name' => 'required|string|min:2|max:64',
            'bank_agency' => 'required|string|min:4|max:64',
            'bank_account' => "required|string|min:4|max:64|unique:banks,id,{$this->id}",
        ];
    }
}
