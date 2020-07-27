<?php

namespace App\Helpers;

class Number
{
    public function setReal($number): float
    {
        $number = preg_replace("/[^0-9]/", "", $number);
        return (float) $number;
    }

    public function setInt($number): int
    {
        $number = preg_replace("/[^0-9]/", "", $number);
        return (int) $number;
    }

    public function setMoney($value): float
    {
        $value = $this->setReal($value);
        $value = $value;
        return (float) $value;
    }

    public function takeMoneyFormat($value): string
    {
        return number_format($value, 2, ',', '.');
    }

    public function percent($value): float
    {
        $hasDelimiter = preg_match("/,/", $value);
        if ($hasDelimiter) {
            $value = $this->setReal($value) / 100;
            return $value;
        }

        $value = $this->setReal($value);
        return $value;
    }
}
