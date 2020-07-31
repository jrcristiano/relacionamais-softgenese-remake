<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Receive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'receive_award_real_value',
        'receive_taxable_real_value',
        'receive_date_receipt',
        'receive_status',
        'receive_demand_id',
    ];

    public function demand()
    {
        return $this->hasOne(Demand::class, 'id', 'receive_demand_id');
    }

    public function getDemandClientNameFormattedAttribute()
    {
        return Str::limit($this->attributes['demand_client_name'], 10, '...');
    }

    public function getNoteStatusFormattedAttribute()
    {
        $status = $this->attributes['note_status'];
        $status == 1 ? 'ABERTO' : ($status == 2 ? 'RECEBIDO' : ($status == 3 ? 'CANCELADO' : ''));
        return $status;
    }

    public function getDemandNfeReceiptFormattedAttribute()
    {
        return Carbon::parse($this->attributes['demand_nfe_receipt'])->format('d/m/Y');
    }

    public function getDemandNfeStatusFormattedAttribute()
    {
        $statusAtribute = $this->attributes['demand_nfe_status'];
        $status = $statusAtribute == 1 ? 'PAGO' : ($statusAtribute == 2 ? 'PENDENTE' : ($statusAtribute == 3 ? 'CANCELADO' : ''));
        return $status;
    }

    public function setReceiveAwardRealValueAttribute($value)
    {
        $this->attributes['receive_award_real_value'] = toReal($value);
    }

    public function setReceiveTaxableRealValueAttribute($value)
    {
        $this->attributes['receive_taxable_real_value'] = toReal($value);
    }
}
