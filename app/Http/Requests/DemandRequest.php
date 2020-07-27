<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note_demand_id' => 'nullable',
            'demand_client_cnpj' => 'required|string|max:18',
            'demand_client_name' => 'required|string|min:3|max:255',
            'demand_prize_amount' => 'required|string|min:1',
            'demand_taxable_amount' => 'nullable',
            'demand_taxable_manual' => 'nullable',
            'demand_other_value' => 'nullable'
        ];
    }
}
