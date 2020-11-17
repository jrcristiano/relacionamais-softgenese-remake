<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AcessoCard extends Model
{
    protected $fillable = [
        'acesso_card_document',
        'acesso_card_name',
        'acesso_card_value',
        'acesso_card_number',
        'acesso_card_proxy',
        'acesso_card_already_exists',
        'acesso_card_spreadsheet_line',
        'acesso_card_demand_id',
        'acesso_card_award_id',
        'acesso_card_chargeback',
        'acesso_card_generated',
        'acesso_card_award_parted',
    ];

    public function getAcessoCardNameFormattedAttribute()
    {
        return strtoupper($this->attributes['acesso_card_name']);
    }

    public function getAcessoCardValueFormattedAttribute()
    {
        return number_format($this->attributes['acesso_card_value'], 2, ',', '.');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
