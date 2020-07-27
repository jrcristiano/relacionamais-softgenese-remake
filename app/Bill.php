<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_value',
        'bill_bank_id',
        'bill_payday',
        'bill_due_date',
        'bill_bank_id',
        'bill_payment_status',
        'bill_provider_id',
        'bill_note'
    ];

    public function setBillValueAttribute($billValue)
    {
        $billValue = toReal($billValue);
        $this->attributes['bill_value'] = $billValue;
    }

    public function getProviderNameFormattedAttribute()
    {
        return Str::limit($this->attributes['provider_name'], 30, '...');
    }

    public function getBillValueFormattedAttribute()
    {
        return number_format($this->attributes['bill_value'], 2, ',', '.');
    }

    public function getBillBankNameAgencyAccountFormattedAttribute()
    {
        $bankName = $this->attributes['bank_name'];
        $bankAgency = $this->attributes['bank_agency'];
        $bankAccount = $this->attributes['bank_account'];

        return "{$bankName} | AG {$bankAgency} | Conta {$bankAccount}";
    }

    public function getBillPaydayFormattedAttribute()
    {
        return Carbon::parse($this->attributes['bill_payday'])->format('d/m/Y');
    }

    public function getBillDueDateFormattedAttribute()
    {
        return Carbon::parse($this->attributes['bill_due_date'])->format('d/m/Y');
    }

    public function getBillTotalAttribute()
    {
        $value = $this->attributes['bill_total'] ?? 0;
        return takeMoneyFormat($value);
    }

    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bill_bank_id');
    }

    public function provider()
    {
        return $this->hasOne(Bank::class, 'id', 'bill_provider_id');
    }

    public function bills()
    {
        return $this->hasMany(CashFlow::class, 'flow_bill_id', 'id');
    }
}
