<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    const PATRIMONY = 1;
    const AWARD = 2;

    protected $fillable = [
        'transfer_account_credit',
        'transfer_account_debit',
        'transfer_value',
        'transfer_type',
        'transfer_date'
    ];

    public function setTransferValueAttribute($value)
    {
        $value = toReal($value);
        $this->attributes['transfer_value'] = $value;
    }

    public function getTransferValueFormattedAttribute()
    {
        $transferValue = $this->attributes['transfer_value'];
        return number_format($transferValue, 2, ',', '.');
    }

    public function transferDebit()
    {
        return $this->belongsTo(Bank::class, 'transfer_account_debit', 'id');
    }

    public function transferCredit()
    {
        return $this->belongsTo(Bank::class, 'transfer_account_credit', 'id');
    }

    public function getTransferDateFormattedAttribute()
    {
        return Carbon::parse($this->attributes['transfer_date'])->format('d/m/Y');
    }
}
