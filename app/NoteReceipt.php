<?php

namespace App;

use App\Helpers\Number;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NoteReceipt extends Model
{
    protected $fillable = [
        'note_receipt_award_real_value',
        'note_receipt_taxable_real_value',
        'note_receipt_other_value',
        'note_receipt_account_id',
        'note_receipt_date',
        'note_receipt_id',
        'note_receipt_demand_id',
        'note_receipt_note',
    ];

    public function getNoteReceiptTaxableRealValueFormattedAttribute()
    {
        $value = number_format($this->attributes['note_receipt_taxable_real_value'], 2, ',', '.');
        $formattedValue = "R$ {$value}";

        return $formattedValue;
    }

    public function getNoteReceiptOtherValueFormattedAttribute()
    {
        $value = number_format($this->attributes['note_receipt_other_value'], 2, ',', '.');
        $formattedValue = "R$ {$value}";

        return $formattedValue;
    }

    public function getNoteReceiptAwardRealValueFormattedAttribute()
    {
        $value = number_format($this->attributes['note_receipt_award_real_value'], 2, ',', '.');
        $formattedValue = "R$ {$value}";

        return $formattedValue;
    }

    public function setNoteReceiptAwardRealValueAttribute($value)
    {
        $this->attributes['note_receipt_award_real_value'] = toReal($value);
    }

    public function setNoteReceiptTaxableRealValueAttribute($value)
    {
        $this->attributes['note_receipt_taxable_real_value'] = toReal($value);
    }

    public function setNoteReceiptOtherValueAttribute($value)
    {
        $this->attributes['note_receipt_other_value'] = toReal($value);
    }

    public function getNoteReceiptDateFormattedAttribute()
    {
        return Carbon::parse($this->attributes['note_receipt_date'])->format('d/m/Y');
    }
}
