<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_company' => 'required|string|min:3|max:64',
            'client_address' => 'required|string|min:5|max:64',
            'client_email' => "required|min:8|max:255|unique:clients,id,{$this->id}",
            'client_phone' => 'required|string|min:8|max:16',
            'client_responsable_name' => 'required|string|min:3|max:64',
            'client_cnpj' => "required|string|min:14|max:18|unique:clients,id,{$this->id}",
            'client_manager' => 'nullable|integer',
            'client_rate_admin' => 'required|string|min:1',
            'client_comission_manager' => 'required|string|min:1',
            'client_state_reg' => 'nullable'
        ];
    }
}
