<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    protected $fillable = [
        'client_company',
        'client_address',
        'client_phone',
        'client_responsable_name',
        'client_cnpj',
        'client_manager',
        'client_rate_admin',
        'client_comission_manager',
        'client_state_reg',
        'client_email',
    ];

    public function getClientRateAdminFormattedAttribute()
    {
        $rateAdmin = number_format($this->attributes['client_rate_admin'], 1, ',', '');
        return "{$rateAdmin}%";
    }

    public function getClientComissionManagerFormattedAttribute()
    {
        $commissionManager = number_format($this->attributes['client_comission_manager'], 1, ',', '');
        return "{$commissionManager}%";
    }

    public function getClientRateAdminFormAttribute()
    {
        return $this->attributes['client_rate_admin'] * 100;
    }

    public function getClientComissionManagerFormAttribute()
    {
        return $this->attributes['client_comission_manager'] * 100;
    }

    public function getClientCompanyFormattedAttribute()
    {
        return Str::limit($this->attributes['client_company'], 30, '...');
    }

    public function getClientCompanyAttribute()
    {
        return strtoupper($this->attributes['client_company']);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public function setClientCompanyAttribute($value)
    {
        $this->attributes['client_company'] = strtolower($value);
    }

    public function setClientAddressAttribute($value)
    {
        $this->attributes['client_address'] = strtolower($value);
    }

    public function setClientResponsableNameAttribute($value)
    {
        $this->attributes['client_responsable_name'] = strtolower($value);
    }

    public function setClientPhoneAttribute($value)
    {
        $value = toIntLiteral($value);
        $this->attributes['client_phone'] = str_pad($value, 11, '0', STR_PAD_RIGHT);
    }

    public function setClientStateRegAttribute($value)
    {
        $this->attributes['client_state_reg'] = $value ? strtolower($value) : 'não informado';
    }

    public function setClientCnpjAttribute($value)
    {
        $value = toIntLiteral($value);
        $this->attributes['client_cnpj'] = str_pad($value, 14, '0', STR_PAD_RIGHT);
    }

    public function setClientRateAdminAttribute($clientRateAdmin)
    {
        $this->attributes['client_rate_admin'] = toPercent($clientRateAdmin);
    }

    public function setClientComissionManagerAttribute($clientComissionManager)
    {
        $this->attributes['client_comission_manager'] = toPercent($clientComissionManager);
    }

    public function manager()
    {
        return $this->hasOne(Manager::class, 'id', 'client_manager');
    }
}
