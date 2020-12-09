<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseAcessoCardsCompra extends Model
{
    protected $table = 'base_acesso_cards_compras';
    protected $fillable = [
        'base_acesso_card_name',
        'base_acesso_card_cpf',
        'base_acesso_card_generated',
        'base_acesso_card_award_id',
        'base_acesso_card_status'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_RESERVED = 3;

    public function getBaseAcessoCardNameFormattedAttribute()
    {
        return strtoupper($this->attributes['base_acesso_card_name']);
    }
}
