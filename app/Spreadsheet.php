<?php

namespace App;

use App\Helpers\Text;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spreadsheet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'spreadsheet_name',
        'spreadsheet_document',
        'spreadsheet_value',
        'spreadsheet_bank',
        'spreadsheet_agency',
        'spreadsheet_account',
        'spreadsheet_account_type',
        'spreadsheet_award_id',
        'spreadsheet_keyline',
        'spreadsheet_shipment_file_path',
        'spreadsheet_chargeback',
        'spreadsheet_demand_id',
    ];

    const DOCUMENT_NUMBER = '30740059000187';
    const AGENCY = '00078';
    const ACCOUNT = '000000094200';
    const COMPANY_NAME = 'RELACIONAMAIS MARKETING LTDA';
    const COMPANY_ADDRESS = 'AV GUILHERME';
    const NUMBER_ADDRESS = '01515';
    const CITY = 'SÃO PAULO';
    const CEP = '02053003';
    const STATE = 'SP';

    public function setSpreadsheetValueAttribute($value)
    {
        $this->attributes['spreadsheet_value'] = $value;
    }

    public function setSpreadsheetAgencyAttribute($value)
    {
        $value = str_pad($value, 4, '0', STR_PAD_LEFT);
        $value = Text::clean($value);
        $this->attributes['spreadsheet_agency'] = substr($value, 0, 4);
    }

    public function setSpreadsheetDocumentAttribute($value)
    {
        $value = Text::cleanDocument($value);
        $this->attributes['spreadsheet_document'] = $value;
    }

    public function setSpreadsheetAccountAttribute($value)
    {
        $value = toIntLiteral($value);
        $value = (string) $value;
        $this->attributes['spreadsheet_account'] = str_replace('.', '', $value);
    }

    public function setSpreadsheetNameAttribute($value)
    {
        $value = strtoupper($value);
        $this->attributes['spreadsheet_name'] = rtrim(ltrim($value));
    }

    public function setSpreadsheetBankAttribute($value)
    {
        $this->attributes['spreadsheet_bank'] = str_pad($value, 3, '0', STR_PAD_LEFT);
    }

    public function getSpreadsheetAccountTypeFormattedAttribute()
    {
        $spreadsheetType = $this->attributes['spreadsheet_account_type'];
        return $spreadsheetType == null || $spreadsheetType == 'C' ? 'CONTA CORRENTE' : 'CONTA POUPANÇA';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public function getSpreadsheetBankAttribute()
    {
        return str_pad($this->attributes['spreadsheet_bank'], 3, '0', STR_PAD_LEFT);
    }

    public function getSpreadsheetAgencyAttribute()
    {
        return str_pad($this->attributes['spreadsheet_agency'], 4, '0', STR_PAD_LEFT);
    }

    public function getSpreadsheetValueAttribute()
    {
        return number_format($this->attributes['spreadsheet_value'], 2, ',', '.');
    }
}
