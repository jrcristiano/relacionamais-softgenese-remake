<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AwardRequest extends FormRequest
{
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
            'awarded_upload_table' => !$this->id ? 'required' : 'nullable',
        ];
    }
}
