<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Provider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'provider_name',
        'provider_address',
        'provider_cnpj',
        'provider_note'
    ];

    public function getProviderNameFormattedAttribute()
    {
        $value = strtoupper($this->attributes['provider_name']);
        return Str::limit($value, 30, '...');
    }

    public function getProviderNameAttribute()
    {
        $value = strtoupper($this->attributes['provider_name']);
        return $value;
    }

    public function setProviderNameAttribute($value)
    {
        return $this->attributes['provider_name'] = strtolower($value);
    }

    public function getProviderAddressFormattedAttribute()
    {
        return Str::limit($this->attributes['provider_address'], 30, '...');
    }

    public function setProviderAddressAttribute($value)
    {
        return $this->attributes['provider_address'] = strtolower($value);
    }

    public function setProviderCnpjAttribute($providerCnpj)
    {
        $value = toIntLiteral($providerCnpj);
        $this->attributes['provider_cnpj'] = str_pad($value, 11, '0', STR_PAD_LEFT);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
