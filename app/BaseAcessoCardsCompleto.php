<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseAcessoCardsCompleto extends Model
{
    protected $table = 'base_acesso_cards_completo';
    protected $fillable = [
        'base_acesso_card_name',
        'base_acesso_card_cpf',
    ];
}
