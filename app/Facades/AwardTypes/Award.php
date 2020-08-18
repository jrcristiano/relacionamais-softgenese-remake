<?php

namespace App\Facades\AwardTypes;

abstract class Award
{
    abstract public function storeAward($file, array $data);
}
