<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'manager_name',
        'manager_phone',
        'manager_email',
        'manager_cpf'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id', 'client_manager');
    }

    public function setManagerNameAttribute($value)
    {
        $this->attributes['manager_name'] = strtolower($value);
    }

    public function setManagerPhoneAttribute($value)
    {
        $phoneNumber = toIntLiteral($value);
        $phoneNumber = (string) $phoneNumber;

        $telOperator = substr($phoneNumber, 0, 2);
        $this->attributes['manager_tel_operator'] = toIntLiteral($telOperator);

        $phoneNumber = substr($phoneNumber, 2, strlen($phoneNumber));
        $this->attributes['manager_phone'] = $phoneNumber == 0 ? str_pad($value, 11, '0', STR_PAD_LEFT) : $phoneNumber;
    }

    public function setManagerCpfAttribute($value)
    {
        $value = toIntLiteral($value);
        $this->attributes['manager_cpf'] = $value == 0 ? str_pad($value, 11, '0', STR_PAD_LEFT) : $value;
    }

    public function setManagerEmailAttribute($value)
    {
        return $this->attributes['manager_email'] = strtolower($value);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
