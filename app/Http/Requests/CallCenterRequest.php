<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallCenterRequest extends FormRequest
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
            'call_center_award_type' => 'required|min:1',
            'call_center_subproduct' => 'required|min:1',
            'call_center_acesso_card_id' => 'nullable',
            'call_center_reason' => 'required|min:1',
            'call_center_status' => 'required|min:1',
            'call_center_phone' => 'required|min:7|max:255',
            'call_center_email' => 'required|min:3|max:255',
            'call_center_comments' => 'nullable',
        ];
    }
}
