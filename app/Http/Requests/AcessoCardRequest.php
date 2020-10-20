<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcessoCardRequest extends FormRequest
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
            'awarded_type' => 'required|min:1',
            'awarded_status' => 'required|min:1|in:1,2,3,4',
            'awarded_upload_table' => $this->id ? 'nullable' : 'required',
        ];
    }
}
