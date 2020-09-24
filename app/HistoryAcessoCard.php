<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HistoryAcessoCard extends Model
{
    protected $table = 'history_acesso_cards';
    protected $fillable = [
        'history_base_id',
        'history_acesso_card_id',
        'history_acesso_card_generated'
    ];

    public function getAcessoCardValueFormattedAttribute()
    {
        return number_format($this->attributes['acesso_card_value'], 2, ',', '.');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
