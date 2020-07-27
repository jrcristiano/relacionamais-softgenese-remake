<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Layout;

class TrailerFileRow extends ShipmentRow
{
    protected $row = [];

    public function addQuantityRegister($quantityRegister)
    {
        $maxChar = 6;
        $this->row[] = str_pad($quantityRegister, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }

    public function addQuantityLote($quantityLote)
    {
        $maxChar = 6;
        $this->row[] = str_pad($quantityLote, $maxChar, '0', STR_PAD_LEFT);
        return $this;
    }
}

