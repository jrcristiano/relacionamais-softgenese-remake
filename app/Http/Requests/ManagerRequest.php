<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'manager_name' => 'required|string|min:3|max:64|',
            'manager_phone' => 'required|string|min:8|max:16',
            'manager_email' => 'required|email|min:8|max:128',
            'manager_cpf' => "required|string|min:11|max:14|unique:managers,id,{$this->id}"
        ];
    }
}
