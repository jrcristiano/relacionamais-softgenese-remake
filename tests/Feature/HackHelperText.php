<?php

namespace Tests\Feature;

use Tests\TestCase;

class HackHelperText extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMakeTextCleaned()
    {
        $text = 'Ólá, mùndò!';
        $this->assertEquals('Ola, mundo!', $text);
    }
}
