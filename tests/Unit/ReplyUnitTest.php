<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyUnitTest extends TestCase
{
    protected $thread;
    use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    public function test_reply_has_user()
    {
        $reply = create('App\Reply');
        $this->assertInstanceOf('App\User', $reply->user);
    }

    public function test_thread_have_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_thread_has_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->user);
    }

    public function test_thread_can_add_reply()
    {
        $this->thread->addReply([
            'body' => 'fooBar',
            'user_id' => 1
        ]);
        $this->assertCount(1, $this->thread->replies);
    }
}
