<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Demand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'demand_client_cnpj',
        'demand_client_name',
        'demand_prize_amount',
        'demand_taxable_amount',
        'demand_taxable_manual',
        'demand_nfe_number',
        'demand_nfe_status',
        'demand_nfe_receipt',
        'demand_other_value',
        'demand_nfe_total',
        'deleted_at'
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'demand_client_cnpj', 'client_cnpj');
    }

    public function spreadsheet()
    {
        return $this->hasOne(Spreadsheet::class, 'spreadsheet_award_id', 'id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'awarded_demand_id', 'id');
    }

    public function notes()
    {
        return $this->hasOne(Note::class, 'note_demand_id', 'id');
    }

    public function setDemandClientNameAttribute($value)
    {
        $value = explode('|', $value);
        $value = trim($value[0]);
        $this->attributes['demand_client_name'] = $value;
    }

    public function getDemandClientNameAttribute()
    {
        $value = $this->attributes['demand_client_name'];
        return strtoupper($value);
    }

    public function getDemandClientNameFormattedAttribute()
    {
        return Str::limit($this->attributes['demand_client_name'], 25, '...');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public function getDemandPrizeAmountFormattedAttribute()
    {
        $demandPrizeAmount = $this->attributes['demand_prize_amount'];
        return number_format($demandPrizeAmount, 2, ',', '.');
    }

    public function getDemandTaxableManualFormattedAttribute()
    {
        $demandTaxableManual = $this->attributes['demand_taxable_manual'];
        return number_format($demandTaxableManual, 2, ',', '.');
    }

    public function getDemandTaxableAmountFormattedAttribute()
    {
        $demandTaxableAmount = $this->attributes['demand_taxable_amount'];
        return number_format($demandTaxableAmount, 2, ',', '.');
    }

    public function getDemandOtherValueFormattedAttribute()
    {
        $otherValue = $this->attributes['demand_other_value'];
        return number_format($otherValue, 2, ',', '.');
    }

    public function getDemandNfeReceiptFormattedAttribute()
    {
        return Carbon::parse($this->attributes['demand_nfe_receipt'])->format('d/m/Y');
    }

    public function getDemandNfeTotalFormattedAttribute()
    {
        return number_format($this->attributes['demand_nfe_total'], 2, ',', '.');;
    }

    public function getDemandNfeStatusFormattedAttribute()
    {
        $statusAtribute = $this->attributes['demand_nfe_status'];
        $status = $statusAtribute == 1 ? 'Aberto' : ($statusAtribute == 2 ? 'Recebido' : 'Cancelado');
        return $status;
    }

    public function getSaleAttribute()
    {
        $sale = getShipmentValueById($this->attributes['id']);
        $sale = round($sale, 3);
        return $sale;
    }

    public function getSaleFormattedAttribute()
    {
        $sale = getShipmentValueById($this->attributes['id']);
        $sale = round($sale, 3);
        return $sale;
    }

    public function getReceivePrizeAmountFormattedAttribute()
    {
        return number_format($this->attributes['receive_prize_amount'], 2, ',', '.');
    }

    public function getReceiveTaxableAmountFormattedAttribute()
    {
        return number_format($this->attributes['receive_taxable_amount'], 2, ',', '.');
    }

    public function getAwardTotalFormattedAttribute()
    {
        return number_format($this->attributes['award_total'], 2, ',', '.');
    }

    /* Dados de notes (exibido em contas a receber) */

    public function getNoteReceiptDateFormattedAttribute()
    {
        if ($this->attributes['note_receipt_date']) {
            return Carbon::parse($this->attributes['note_receipt_date'])->format('d/m/Y');
        }

        return '---';
    }

    public function getNoteCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['note_created_at'])->format('d/m/Y');
    }

    public function getNoteDueDateFormattedAttribute()
    {
        return Carbon::parse($this->attributes['note_due_date'])->format('d/m/Y');
    }

    public function getNoteStatusFormattedAttribute()
    {
        $statusAtribute = $this->attributes['note_status'];
        $status = $statusAtribute == 1 ? 'Aberto' : ($statusAtribute == 2 ? 'Recebido' : 'Cancelado');
        return $status;
    }
}
