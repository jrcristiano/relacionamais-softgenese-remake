<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AcessoCardShopping extends Model
{
    protected $table = 'acesso_card_shoppings';
    protected $fillable = [
        'acesso_card_shopping_document',
        'acesso_card_shopping_name',
        'acesso_card_shopping_value',
        'acesso_card_shopping_number',
        'acesso_card_shopping_proxy',
        'acesso_card_shopping_already_exists',
        'acesso_card_shopping_spreadsheet_line',
        'acesso_card_shopping_demand_id',
        'acesso_card_shopping_award_id',
        'acesso_card_shopping_chargeback',
        'acesso_card_shopping_generated',
        'acesso_card_shopping_award_parted',
    ];

    public function getAcessoCardNameFormattedAttribute()
    {
        return strtoupper($this->attributes['acesso_card_shopping_name']);
    }

    public function getAcessoCardValueFormattedAttribute()
    {
        return number_format($this->attributes['acesso_card_shopping_value'], 2, ',', '.');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
