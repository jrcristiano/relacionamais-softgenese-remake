<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcessoCard extends Model
{
    protected $fillable = [
        'acesso_card_document',
        'acesso_card_name',
        'acesso_card_value',
        'acesso_card_spreadsheet_line',
        'acesso_card_demand_id',
        'acesso_card_award_id',
    ];

    public function getAcessoCardValueAttribute()
    {
        return number_format($this->attributes['acesso_card_value'], 2, ',', '.');
    }
}
