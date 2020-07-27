<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Layout;

class RegisterDetailRow extends ShipmentRow
{
    protected $row = [];

    public function addRegisterType($registerType)
    {
        $this->row[] = str_pad($registerType, 1, '', STR_PAD_LEFT);
        return $this;
    }

    public function addMovementType($movementType = null)
    {
        $maxChar = 3;
        $this->row[] = str_pad($movementType, $maxChar, '0', STR_PAD_RIGHT);
        return $this;
    }

    public function addRegisterNumber($registerNumber)
    {
        $maxChar = 5;
        $this->row[] = str_pad($registerNumber, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addSegment($segment)
    {
        $this->row[] = $segment;
        return $this;
    }

    public function addChamber($chamber = null)
    {
        $maxChar = 3;
        $this->row[] = str_pad($chamber, $maxChar, '0', STR_PAD_RIGHT);
        return $this;
    }

    public function addNumberCreatedByCompany($document)
    {
        $this->row[] = str_pad($document, 20, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addCurrency($currency)
    {
        if (is_string($currency)) {
            $currency = strtoupper($currency);
        }

        $this->row[] = str_pad($currency, 3, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addSBPCode($sbpCode)
    {
        $this->row[] = $sbpCode;
        return $this;
    }

    public function addNoteFiscal($noteFiscal)
    {
        if (!$noteFiscal) {
            $noteFiscal = '';
        }
        $noteFiscal = "NF {$noteFiscal}";
        $this->row[] = str_pad($noteFiscal, 18, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addPaymentEffectiveValue($paymentValue)
    {
        $paymentValue = toIntLiteral($paymentValue);
        $maxChar = 15;

        $this->row[] = str_pad($paymentValue, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }
}
