<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Layout;

use App\Helpers\Text;

abstract class ShipmentRow
{
    public function addFixedBankCode($bankCode = null)
    {
        if (!$bankCode) {
            $bankCode = env('CNAB240_BANK_CODE');
        }

        $this->row[] = str_pad($bankCode, 3, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addFixedNumberAddress($numberAddress = null)
    {
        $maxChar = 5;
        if (!$numberAddress) {
            $numberAddress = env('CNAB240_NUMBER_ADDRESS');
        }

        $this->row['number_address'] = str_pad($numberAddress, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addFixedCompanyAddress($companyName = null)
    {
        if (!$companyName) {
            $companyName = env('CNAB240_COMPANY_NAME');
        }

        $this->row['company_address'] = str_pad($companyName, 30, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addWhiteSpace(string $quantity)
    {
        $this->row[] = str_pad('', $quantity, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addConstantNumber(string $number)
    {
        $maxChar = 5;
        $this->row[] = str_pad($number, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addDebitAccount(string $debitAccount)
    {
        $debitAccount = substr($debitAccount, 0, -1);
        $this->row[] = str_pad($debitAccount, 6, 0, STR_PAD_LEFT);
        return $this;
    }

    public function addDebitAccountOthersBanks(string $debitAccountOthersBanks)
    {
        $debitAccount = substr($debitAccountOthersBanks, 0, -1);
        $debitAccount = substr($debitAccount, 0, 12);
        $this->row[] = str_pad($debitAccount, 12, 0, STR_PAD_LEFT);
        return $this;
    }

    public function addDebitAgency(string $debitAgency)
    {
        $debitAgency = str_pad($debitAgency, 4, 0, STR_PAD_LEFT);
        $debitAgency = substr($debitAgency, 0, 4);
        $this->row[] = $debitAgency;
        return $this;
    }

    public function addDebitDocumentNumber(string $debitDocumentNumber)
    {
        $debitDocumentNumber = toIntLiteral($debitDocumentNumber);
        $this->row[] = str_pad($debitDocumentNumber, 14, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addDocumentType(string $documentType)
    {
        $this->row[] = $documentType;
        return $this;
    }

    public function addFixedDocumentNumber($documentNumber = null)
    {
        if (!$documentNumber) {
            $documentNumber = env('CNAB240_DOCUMENT_NUMBER');
        }

        $this->row[] = str_pad($documentNumber, 14, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addFixedAgency($agency = null)
    {
        if (!$agency) {
            $agency = env('CNAB240_AGENCY');
        }

        $this->row[] = str_pad($agency, 5, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addFixedAccount($account = null)
    {
        if (!$account) {
            $account = env('CNAB240_ACCOUNT');
        }
        $this->row[] = str_pad($account, 6, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addDAC(string $dac)
    {
        $dac = substr($dac, -1);
        if ($dac == 'x' || $dac == 'X') {
            $dac = '0';
        }

        $this->row[] = str_pad($dac, 1, '1', STR_PAD_LEFT);
        return $this;
    }

    public function addFixedCompanyName($companyName = null)
    {
        $maxChar = 30;
        if (!$companyName) {
            $companyName = env('CNAB240_COMPANY_NAME');
        }

        $this->row[] = str_pad($companyName, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addCodeLote(string $codeLote)
    {
        $maxChar = 4;
        $this->row[] = str_pad($codeLote, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addPayeeName($payeeName)
    {
        $maxChar = 30;
        $payeeName = Text::upper($payeeName);

        $this->row[] = str_pad($payeeName, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addDateGeneration(string $dayMouthYear = null)
    {
        $date = new \DateTime();
        $dayMouthYear = toIntLiteral($dayMouthYear);

        $this->row[] = $dayMouthYear != null ? $dayMouthYear : $date->format('dmY');
        return $this;
    }

    public function addHourGeneration($hourMinuteSecond = null)
    {
        $date = new \DateTime();
        $hourMinuteSecond = $hourMinuteSecond ? toIntLiteral($hourMinuteSecond) : $date->format('his');
        $this->row[] = $hourMinuteSecond;
        return $this;
    }

    public function addTypeRegister(string $typeRegister)
    {
        $this->row[] = str_pad($typeRegister, 1, '1', STR_PAD_RIGHT);
        return $this;
    }

    public function addZeros(int $quantity)
    {
        $this->row[] = str_pad('', $quantity, '0', STR_PAD_RIGHT);
        return $this;
    }

    public function addPaymentValue($paymentValue, $maxChar = 15)
    {
        $paymentValue = toIntLiteral($paymentValue);
        $this->row[] = str_pad($paymentValue, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addQuantityRegister($quantityRegister)
    {
        $this->row['quantity_register'] = str_pad($quantityRegister, 6, '0', STR_PAD_LEFT);
        return $this;
    }

    public function get($name = null)
    {
        return isset($name) ? $this->row[$name] : $this->row;
    }
}
