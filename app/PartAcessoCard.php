<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartAcessoCard extends Model
{
    protected $fillable = [
        'part_acesso_card_name',
        'part_acesso_card_document',
        'part_acesso_card_value',
        'part_acesso_card_number',
        'part_acesso_card_number',
        'part_acesso_card_proxy',
        'part_acesso_card_chargeback',
        'part_acesso_card_generated',
        'part_acesso_card_demand_id',
        'part_acesso_card_award_id',
    ];
}
