<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CallCenter extends Model
{
    protected $fillable = [
        'call_center_award_type',
        'call_center_subproduct',
        'call_center_acesso_card_id',
        'call_center_reason',
        'call_center_status',
        'call_center_phone',
        'call_center_email',
        'call_center_comments',
    ];

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
