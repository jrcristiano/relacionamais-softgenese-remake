<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryAcessoCard extends Model
{
    protected $table = 'history_acesso_cards';
    protected $fillable = [
        'history_base_id',
        'history_acesso_card_id',
    ];
}
