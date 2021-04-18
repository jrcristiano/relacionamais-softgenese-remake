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

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'flow_bill_id', 'id');
    }

    public function getDrawerAttribute()
    {
        $billDrawer = $this->bill->provider->provider_name_formatted ?? null;
        /*$invoiceReceiptDrawer = $this->invoiceReceipt->demand
            ->client
            ->name_upper_limited ?? null;

        $transferDrawer = 'TRANSFERÊNCIA';*/

        $drawer = $billDrawer;
        return $drawer;
    }

    public function getDocumentAttribute()
    {
        $billDocument = $this->bill->id ?? null;
        $billDocument = $billDocument ? "ID {$billDocument}" : null;

        /*$invoiceReceiptDocument = $this->invoiceReceipt->invoice->number ?? null;
        $invoiceReceiptDocument = $invoiceReceiptDocument ? "NOTA {$invoiceReceiptDocument}" : null;

        $transferDocument = $this->transfer->id ?? null;
        $transferDocument = $transferDocument ? "ID {$transferDocument}" : null;*/

        $document = $billDocument;
        return $document;
    }

    public function getBankAttribute()
    {
        $billBank = $this->bill->bank->bank_agency_and_account_upper ?? null;
        // $invoiceReceiptBank = $this->invoiceReceipt->bank->bank_agency_and_account_upper_formatted ?? null;

        // $creditedAccount = $this->transfer->creditedAccount->bank_agency_and_account_upper_formatted ?? null;
        // $debitedAccount = $this->transfer->debitedAccount->bank_agency_and_account_upper_formatted ?? null;

        $type = $this->attributes['flow_transfer_credit_or_debit'];
        // if ($type === $this->movementTypes[0]) $transferBank = $creditedAccount;
        // if ($type === $this->movementTypes[0]) $transferBank = $debitedAccount;

        $bank = $billBank;
        return $bank;
    }

    public function getDebitPatrimonyValueMoneyAttribute()
    {
        $type = $this->attributes['movement_type'];

        if ($type == $this->movementTypes[1]) {
            $billValue = $this->bill->negative_value ?? null;
            $transferEquityValue = $this->transfer->negative_value ?? null;

            $patrimonyValueMoney = $billValue ?? $transferEquityValue;
            return number_format($patrimonyValueMoney, 2, ',', '.');
        }

        return number_format(0, 2, ',', '.');
    }

    public function getCreditAwardValueMoneyAttribute()
    {
        $type = $this->attributes['movement_type'];
        if ($type == $this->movementTypes[2]) {
            $prizeAmountValue = $this->invoiceReceipt->prize_amount ?? null;
            $prizeAmountValue = $prizeAmountValue > 0 ? $prizeAmountValue : null;

            $creditAwardValue = $prizeAmountValue ?? null;
            return number_format($creditAwardValue, 2, ',', '.');
        }

        return number_format(0, 2, ',', '.');
    }

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
