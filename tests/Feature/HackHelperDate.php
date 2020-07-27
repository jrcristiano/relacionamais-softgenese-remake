<?php

namespace Tests\Feature;

use Tests\TestCase;

class HackHelperDate extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFormattedDate()
    {
        $date = '2020-12-08 00:00:00';
        $this->assertEquals('08/12/2020', $date);
    }
}
