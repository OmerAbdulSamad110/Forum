<?php

namespace Tests\Feature;

use App\Activity;
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

    public function test_guest_not_see_thread_create()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

    public function test_thread_has_string_path()
    {
        $thread = create('App\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    public function test_auth_owner_delete_thread()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $favorite = create('App\Favorite', ['favorable_id' => $reply->id]);
        $this->delete($thread->path());
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('favorites', ['id' => $favorite->id]);
        $this->assertEquals(0, Activity::count());
    }

    public function test_unauth_user_cannot_delete_thread()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $this->delete($thread->path())
            ->assertRedirect('/login');
        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }

    public function test_unauth_user_cannot_delete_reply()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');
        $this->signIn();
        $this->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_reply_owner_delete_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function test_thread_owner_delete_any_reply()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function test_auth_owner_update_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->put("/replies/{$reply->id}", ['body' => 'cahnges occur']);
        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => 'cahnges occur'
        ]);
    }

    public function test_unauth_user_not_update_reply()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->put("/replies/{$reply->id}", ['body' => 'cahnges occur'])
            ->assertRedirect('/login');
        $this->signIn();
        $this->put("/replies/{$reply->id}", ['body' => 'cahnges occur'])
            ->assertStatus(403);
    }

    public function test_check_spam_in_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'yahoo customer service'
        ]);
        $this->expectException(\Exception::class);
        $this->post($thread->path() . '/replies', $reply->toArray());
    }
}
