<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    protected $thread;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }
    public function test_view_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    public function test_view_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    public function test_view_single_thread_replies()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
        $response = $this->get('/threads/channel/1');
        $response->assertSee($reply->body);
    }

    public function test_guest_not_see_thread_create()
    {
        $this->withExceptionHandling()->get('/threads/create')->assertRedirect('/login');
    }

    public function test_thread_has_string_path()
    {
        $thread = create('App\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }
}
