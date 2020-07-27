<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CashFlow extends Model
{
    protected $fillable = [
        'flow_movement_date',
        'flow_bank_id',
        'flow_receive_id',
        'flow_bill_id',
        'flow_award_id',
        'bill_payment_date',
        'receive_payment_date',
        'flow_demand_id',
        'flow_transfer_id',
        'flow_transfer_credit_or_debit',
        'flow_hide_line'
    ];

    protected $flowTypeTransfers = [
        1 => 'Credit',
        2 => 'Debit'
    ];

    protected $movementTypes = [
        1 => 'Conta a pagar',
        2 => 'Conta a receber'
    ];

    public function getClientCompanyFormattedAttribute()
    {
        return Str::limit($this->attributes['client_company'], 30, '...');
    }

    public function getProviderNameFormattedAttribute()
    {
        return Str::limit($this->attributes['provider_name'], 30, '...');
    }

    public function getTransferValueFormattedAttribute()
    {
        return number_format($this->attributes['transfer_value'], 2, ',','.');
    }

    public function getShipmentValueFormattedAttribute()
    {
        return number_format($this->attributes['shipment_value'], 2, ',', '.');
    }

    public function getFlowMovementDateFormattedAttribute()
    {
        return Carbon::parse($this->attributes['flow_movement_date'])->format('d/m/Y');
    }

    public function getAwardValueAttribute()
    {
        return number_format($this->attributes['award_value'], 2, ',', '.');
    }

    public function getPatrimonyAttribute()
    {
        $patrimony = $this->attributes['patrimony'] + $this->attributes['other_value'];
        return number_format($patrimony, 2, ',', '.');
    }

    public function getBillValueFormattedAttribute()
    {
        return number_format($this->attributes['bill_value'], 2, ',', '.');
    }
}
