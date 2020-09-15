<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function test_channel_have_threads()
    {
        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);
        $this->assertTrue($channel->threads->contains($thread));
    }
}
