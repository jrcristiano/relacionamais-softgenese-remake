<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau;

use App\Helpers\Text;

abstract class Shipment
{
    protected $instance;

    public function setBankCode($bankCode)
    {
        $bankCode = str_pad($bankCode, 3, '0', STR_PAD_LEFT);
        $bankCode = rtrim(ltrim($bankCode));
        $this->data['bank_code'] = $bankCode;
        return $this;
    }

    public function setDocumentType($documentType)
    {
        $this->data['document_type'] = $documentType;
        return $this;
    }

    public function setValue($value)
    {
        $this->data['value'] = $value;
        return $this;
    }

    public function setNoteFiscal($noteFiscal)
    {
        if (!$noteFiscal) {
            $this->data['note_fiscal'] = '';
        }
        $this->data['note_fiscal'] = $noteFiscal;
        return $this;
    }

    public function setDocumentNumber($documentNumber)
    {
        $this->data['document_number'] = str_pad($documentNumber, 14, '0', STR_PAD_LEFT);
        return $this;
    }

    public function setAgency($agency)
    {
        $agency = toIntLiteral($agency);
        $agency = substr($agency, 0, 5);
        $this->data['agency'] = str_pad($agency, 5, '0', STR_PAD_LEFT);
        return $this;
    }

    public function setAccount($account)
    {
        $account = toIntLiteral($account);
        $this->data['account'] = str_pad($account, 6, '0', STR_PAD_LEFT);
        return $this;
    }

    public function setZeros($quantity)
    {
        $this->fields[] = str_pad('', $quantity, '0', STR_PAD_RIGHT);
        return $this;
    }

    public function setCompanyName($companyName)
    {
        $maxChar = 30;
        $companyName = Text::clean(strtoupper($companyName));

        $this->data['company_name'] = str_pad($companyName, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function setBankName($bankName)
    {
        $bankName = Text::clean(strtoupper($bankName));
        $this->data['bank_name'] = str_pad($bankName, 30, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function setCompanyAddress($companyAddress)
    {
        $this->data['company_address'] = $companyAddress;
        return $this;
    }

    public function setNumberAddress($numberAddress)
    {
        $this->data['number_address'] = $numberAddress;
        return $this;
    }

    public function setCity($city)
    {
        $this->data['city'] = $city;
        return $this;
    }

    public function setCep($cep)
    {
        $this->data['cep'] = $cep;
        return $this;
    }

    public function setState($state)
    {
        $this->data['state'] = $state;
        return $this;
    }

    public function setDebitAgency($debitAgency)
    {
        $debitAgency = toIntLiteral($debitAgency);
        $debitAgency = str_pad($debitAgency, 4, '0', STR_PAD_LEFT);
        $this->data['debit_agency'] = substr($debitAgency, 0, 4);
        return $this;
    }

    public function setDebitAccount($debitAccount)
    {
        $debitAccount = toIntLiteral($debitAccount);
        $debitAgency = str_pad($debitAccount, 6, '0', STR_PAD_LEFT);
        $this->data['debit_account'] = substr($debitAgency, 0, 6);
        return $this;
    }

    public function setDebitAccountOthers($debitAccount)
    {
        $debitAccount = toIntLiteral($debitAccount);
        $debitAccount = str_pad($debitAccount, 12, '0', STR_PAD_LEFT);
        $this->data['debit_account_others'] = substr($debitAccount, 0, 12);
        return $this;
    }

    public function setPayeeName($payeeName)
    {
        $this->data['payee_name'] = substr($payeeName, 0, 29);
        return $this;
    }

    public function setNumberCreatedByCompany($numberCreatedByCompany)
    {
        $this->data['number_created_by_company'] = $numberCreatedByCompany;
        return $this;
    }

    public function setDebitDocumentNumber($debitDocumentNumber)
    {
        $this->data['debit_document_number'] = toIntLiteral($debitDocumentNumber);
        return $this;
    }
}
