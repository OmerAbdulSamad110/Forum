<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_subscribes_to_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');
        $this->assertCount(1, $thread->subscriptions);
    }

    public function test_user_unsubscribes_to_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->delete($thread->path() . '/subscriptions');
        $this->assertCount(0, $thread->subscriptions);
    }
}
