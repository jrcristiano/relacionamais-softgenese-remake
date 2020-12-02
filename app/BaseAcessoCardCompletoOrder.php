<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseAcessoCardCompletoOrder extends Model
{
    protected $table = 'base_acesso_cards_completo_orders';
    protected $fillable = [
        'previous_card_id',
        'currency_card_id'
    ];
}
