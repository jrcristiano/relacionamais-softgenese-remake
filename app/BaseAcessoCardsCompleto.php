<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseAcessoCardsCompleto extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_RESERVED = 3;

    protected $table = 'base_acesso_cards_completo';
    protected $fillable = [
        'base_acesso_card_name',
        'base_acesso_card_cpf',
        'base_acesso_card_generated',
        'base_acesso_card_award_id',
        'base_acesso_card_status'
    ];

    public function setBaseAcessoCardCpfAttribute($value)
    {
        $this->attributes['base_acesso_card_cpf'] = trim($value);
    }

    public function setBaseAcessoCardNumberAttribute($value)
    {
        $this->attributes['base_acesso_card_number'] = trim($value);
    }

    public function setBaseAcessoCardProxyAttribute($value)
    {
        $this->attributes['base_acesso_card_proxy'] = trim($value);
    }
}
