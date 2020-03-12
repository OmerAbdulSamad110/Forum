<?php

namespace Tests\Unit;

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadUnitTest extends TestCase
{
    use DatabaseMigrations;
    public function test_thread_belongs_to_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
}
