<?php

namespace Tests\Unit;

use App\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }
}
