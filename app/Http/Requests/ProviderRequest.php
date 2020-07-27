<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'provider_name' => 'required|string|min:3|max:64',
            'provider_address' => 'required|string|min:3|max:128',
            'provider_cnpj' => "required|string|min:14|max:18|unique:providers,id,{$this->id}",
            'provider_note' => 'string|nullable'
        ];
    }
}
