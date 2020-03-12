<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use DatabaseMigrations;
    public function test_guest_create_no_reply()
    {
        $this->withoutExceptionHandling()->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads/channel/1/reply', []);
    }
    public function test_auth_user_reply_thread()
    {
        $user = create('App\User');
        // Login user
        $this->signIn($user);
        $thread = create('App\Thread');
        $reply = make('App\Reply');
        $this->post($thread->path() . '/reply', $reply->toArray());
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    public function test_guests_not_create_threads()
    {
        $this->withoutExceptionHandling()->expectException('Illuminate\Auth\AuthenticationException');
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
    }

    public function test_auth_user_create_thread()
    {
        $this->signIn();
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function test_thread_required_validation_check()
    {
        $this->publishThread(['channel_id' => null, 'title' => null, 'body' => null])
            ->assertSessionHasErrors(['channel_id', 'title', 'body']);
    }

    public function test_reply_required_validation_check()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);
        $this->post($thread->path() . '/reply', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function test_thread_channel_exists()
    {
        factory('App\Channel')->create();
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
