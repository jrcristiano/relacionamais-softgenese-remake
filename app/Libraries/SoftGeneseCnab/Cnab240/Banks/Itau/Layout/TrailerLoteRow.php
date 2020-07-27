<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Layout;

class TrailerLoteRow extends ShipmentRow
{
    protected $row = [];

    public function addCodeLote($codeLote)
    {
        $maxChar = 4;
        $this->row['code_lote'] = str_pad($codeLote, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addRegisterHeaderLote(string $registerHeaderLote)
    {
        $this->row['register_header_lote'] = $registerHeaderLote;
        return $this;
    }

    public function addOperationType(string $operationType)
    {
        $this->row['operation_type'] = $operationType;
        return $this;
    }

    public function addPaymentType(string $paymentType)
    {
        $this->row['payment_type'] = $paymentType;
        return $this;
    }

    public function addPaymentMethod(string $paymentMethod)
    {
        $this->row['payment_method'] = $paymentMethod;
        return $this;
    }

    public function addLayoutLote(string $layoutLote)
    {
        $this->row['layout_lote'] = $layoutLote;
        return $this;
    }

    public function addFixedCompanyAddress($companyAddress = null)
    {
        $maxChar = 30;
        if (!$companyAddress) {
            $companyAddress = env('CNAB240_ADDRESS');
        }

        $this->row['company_address'] = str_pad($companyAddress, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addFixedNumberAddress($numberAddress = null)
    {
        $maxChar = 5;
        if (!$numberAddress) {
            $numberAddress = env('CNAB240_NUMBER_ADDRESS');
        }

        $this->row['numbers_address'] = str_pad($numberAddress, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addComplementAddress(string $complementAddress)
    {
        $maxChar = 15;
        $this->row['complement_address'] = str_pad($complementAddress, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addFixedCity($city = null)
    {
        $maxChar = 20;
        if (!$city) {
            $city = env('CNAB240_CITY');
        }

        $this->row['city'] = str_pad($city, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addFixedCEP($cep = null)
    {
        if (!$cep) {
            $cep = env('CNAB240_CEP');
        }

        $this->row['cep'] = $cep;
        return $this;
    }

    public function addFixedState($state = null)
    {
        if (!$state) {
            $state = env('CNAB240_STATE');
        }

        $this->row['state'] = $state;
        return $this;
    }

    public function addOccurrence(string $occurrence)
    {
        $maxChar = 10;
        $this->row['occurrence'] = str_pad($occurrence, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }
}

