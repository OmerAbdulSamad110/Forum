<?php

namespace Tests\Feature;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    public function test_invalidate_keywords()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Just a reply'));
        $this->expectException(\Exception::class);
        $spam->detect('yahoo customer service');
    }

    public function test_keyshelddown()
    {
        $spam = new Spam();
        $this->expectException(\Exception::class);
        $spam->detect('aaaaa');
    }
}
