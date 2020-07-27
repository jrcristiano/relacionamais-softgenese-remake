<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Layout;

class HeaderFileTypeZeroRow extends ShipmentRow
{
    protected $row = [];

    public function addBankLote(string $bankLote)
    {
        $maxChar = 4;
        $bankLote = str_pad($bankLote, $maxChar, '0', STR_PAD_LEFT);
        $this->row[] = substr(0, $maxChar, $bankLote);
        return $this;
    }

    public function addLayoutFile(string $layoutFile)
    {
        $this->row[] = $layoutFile;
        return $this;
    }

    public function addFixedBankName($bankName = null)
    {
        $maxChar = 30;
        if (!$bankName) {
            $bankName = env('CNAB240_BANK_NAME');
        }

        $this->row[] = str_pad($bankName, $maxChar, ' ', STR_PAD_RIGHT);
        return $this;
    }

    public function addFileCode(string $fileCode)
    {
        $this->row[] = $fileCode;
        return $this;
    }

    public function addDensityUnity(string $densityUnit)
    {
        $this->row[] = $densityUnit;
        return $this;
    }
}
