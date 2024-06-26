<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bank_name',
        'bank_agency',
        'bank_account',
    ];

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function getBankNameAttribute()
    {
        return strtoupper($this->attributes['bank_name']);
    }

    public function getBankAgencyAndAccountUpperAttribute()
    {
        $bankName = $this->attributes['bank_name'];
        $agency = $this->attributes['bank_agency'];
        $account = $this->attributes['bank_account'];
        $bankAgencyAndAccount = "{$bankName}, AG {$agency} E CONTA {$account}";
        return strtoupper($bankAgencyAndAccount);
    }
}
