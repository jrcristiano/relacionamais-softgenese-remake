<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    protected $fillable = [
        'note_number',
        'note_status',
        'note_due_date',
        'note_receipt_date',
        'note_account_receipt_id',
        'note_demand_id',
        'note_created_at'
    ];

    public function demand()
    {
        return $this->hasOne(Demand::class, 'id', 'note_demand_id');
    }

    public function getDemandPrizeAmountAttribute()
    {
        $demandPrizeAmount = $this->attributes['demand_prize_amount'];
        return number_format($demandPrizeAmount, 2, ',', '.');
    }

    public function getDemandTaxableAmountAttribute()
    {
        $demandTaxableAmount = $this->attributes['demand_taxable_amount'];
        return number_format($demandTaxableAmount, 2, ',', '.');
    }

    public function getDemandOtherValueAttribute()
    {
        $demandTaxableAmount = $this->attributes['demand_other_value'];
        return number_format($demandTaxableAmount, 2, ',', '.');
    }

    public function setDemandPrizeAmountAttribute($value)
    {
        $taxableAmount = toReal($value);
        $this->attributes['demand_prize_amount'] = $taxableAmount;
    }

    public function setDemandTaxableAmountAttribute($value)
    {
        $taxableAmount = toReal($value);
        $this->attributes['demand_taxable_amount'] = $taxableAmount;
    }

    public function setNoteNumberAttribute($value)
    {
        $value = toIntLiteral($value);
        $value = str_pad($value, 4, '0', STR_PAD_LEFT);
        $this->attributes['note_number'] = $value;
    }
}
