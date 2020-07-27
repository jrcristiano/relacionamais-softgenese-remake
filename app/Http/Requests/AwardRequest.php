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
        $cardRule = \Request::get('awarded_type_card') == '1' ? 'required|numeric|min:1' : 'nullable';
        $statusRule = $this->awarded_type == 2 && \Request::get('id') ? 'required|string|min:1' : ($this->awarded_type == 3 && \Request::get('id') ? 'required|string|min:1' : ($this->awarded_type == 1 && \Request::get('id') ? 'required|string|min:1' : 'required'));

        return [
            'awarded_value' => $this->manual_deposit_id && $this->awarded_type == 3 ? 'required|min:1' : 'nullable',
            'awarded_type' => 'required|string|min:1',
            'awarded_status_deposit' => $this->awarded_status_manual ? 'nullable' : 'required',
            'awarded_status_manual' => $this->awarded_status_deposit ? 'nullable' : 'required',
            'awarded_type_card' => $cardRule,
            'awarded_demand_id' => 'required|numeric|min:1',
            'awarded_upload_table' => $this->awarded_status_manual ? 'nullable' : ($this->awarded_type == 2 && $this->id ? 'nullable' : 'required'),
            'awarded_bank_id' => $this->manual_deposit_id && $this->awarded_type == 3 ? 'required|min:1' : 'nullable',
            'awarded_date_payment_manual' => $this->awarded_status_manual ? 'required' : 'nullable',
        ];
    }
}
