<?php

namespace Tests\Feature;

use App\Libraries\HackShipment\Helpers\Number;
use Tests\TestCase;

class HackHelperTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHelperOnlyNumber()
    {
        $number = Number::only(1.899);
        $this->assertEquals(1899, $number);
    }

    public function testHelperStringToOnlyNumber()
    {
        $number = Number::only('1.899,00');
        $this->assertEquals(189900, $number);
    }

    public function testMakeStringToMoneyFormat()
    {
        $money = Number::getMoneyFormat('1499.99');
        $this->assertEquals('1.499,99', $money);
    }

    public function testSetMoneyFormatForDatabase()
    {
        $money = Number::setMoneyFormatForDatabase(100000);
        $this->assertEquals(1000, $money);
    }

    public function testGetDecimalValue()
    {
        $money = Number::decimal(1000.00);
        $this->assertEquals(1000, $money);
    }
}
