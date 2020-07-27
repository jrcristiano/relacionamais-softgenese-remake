<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Layout;

use App\Helpers\Text;

class LoteFileRow extends ShipmentRow
{
    protected $row = [];

    public function addCodeLote($codeLote)
    {
        $maxChar = 4;
        $this->row['code_lote'] = str_pad($codeLote, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addRegisterHeaderLote($registerHeaderLote)
    {
        $this->row['register_header_lote'] = $registerHeaderLote;
        return $this;
    }

    public function addOperationType($operationType)
    {
        $this->row['operation_type'] = $operationType;
        return $this;
    }

    public function addPaymentType($paymentType)
    {
        $this->row['payment_type'] = $paymentType;
        return $this;
    }

    public function addPaymentMethod($paymentMethod)
    {
        $this->row['payment_method'] = $paymentMethod;
        return $this;
    }

    public function addLayoutLote($layoutLote)
    {
        $this->row['layout_lote'] = $layoutLote;
        return $this;
    }

    public function addFixedCompanyAddress($companyAddress = null)
    {
        if (!$companyAddress) {
            $companyAddress = env('CNAB240_COMPANY_ADDRESS');
        }
        $companyAddress = Text::upper($companyAddress);
        $this->row['company_address'] = str_pad($companyAddress, 30, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addComplementAddress($complementAddress)
    {
        $maxChar = 15;
        $complementAddress = Text::upper($complementAddress);
        $this->row['complement_address'] = str_pad($complementAddress, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addFixedCity($city = null)
    {
        if (!$city) {
            $city = env('CNAB240_CITY');
        }

        $city = Text::upper($city);
        $this->row['city'] = str_pad($city, 20, ' ', STR_PAD_RIGHT);
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

        $state = Text::upper($state);
        $this->row['state'] = $state;
        return $this;
    }

    public function addOccurrence(string $occurrence)
    {
        $maxChar = 10;
        $occurrence = Text::upper($occurrence);
        $this->row['occurrence'] = str_pad($occurrence, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }
}
