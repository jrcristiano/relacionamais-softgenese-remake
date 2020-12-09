<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Award extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'awarded_value',
        'awarded_type',
        'awarded_status',
        'awarded_type_card',
        'awarded_upload_table',
        'awarded_shipment_file',
        'awarded_shipment_generated',
        'awarded_shipment_cancelled',
        'awarded_demand_id',
        'awarded_bank_id',
        'awarded_date_payment_manual',
        'award_already_parted',
    ];

    public function spreadsheets()
    {
        return $this->hasMany(Spreadsheet::class, 'spreadsheet_award_id', 'id');
    }

    public function getSaleAttribute()
    {
        return number_format($this->attributes['sale'], 2, ',', '.');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public function getAwardedValueFormattedAttribute()
    {
        return number_format($this->attributes['awarded_value'], 2, ',', '.');
    }

    public function getShipmentFileNameFormattedAttribute()
    {
        $exp = explode('/', $this->attributes['awarded_shipment_file']);
        $value = $exp[1];
        return $value;
    }

    public function setAwardedValueAttribute($value)
    {
        if (\Request::get('awarded_type') == 3) {
            $this->attributes['awarded_value'] = toReal($value);
        }

        if (request('awarded_type') == 1 || request('awarded_type') == 2 || !request('awarded_type') || request('awarded_type') == 4) {
            $this->attributes['awarded_value'] = (float) $value;
        }
    }
}
