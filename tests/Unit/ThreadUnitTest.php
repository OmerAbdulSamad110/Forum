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

    public function test_user_filter_thread_by_channels()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get("/threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_filter_thread_by_user()
    {
        $user = create('App\User');
        $this->signIn($user);
        $threadByTestUser = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByTestUser = create('App\Thread');
        $this->get("/threads?by={$user->name}")
            ->assertSee($threadByTestUser->title)
            ->assertDontSee($threadNotByTestUser->title);
    }

    public function test_filter_by_relpies_count()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
        $threadWithOneReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithOneReplies->id], 1);
        $threadWithNoReplies = create('App\Thread');

        $this->get('/threads?popular=1')
            ->assertSeeInOrder([
                $threadWithTwoReplies->title,
                $threadWithOneReplies->title,
                $threadWithNoReplies->title
            ]);
    }

    public function test_filter_thread_by_unanswered()
    {
        $thread = create('App\Thread');
        // create('App\Reply', ['thread_id' => $thread->id]);
        $this->get('/threads?unanswered=1')
            ->assertSee($thread->title);
    }

    public function test_user_can_subscribe_to_thread()
    {
        $thread = create('App\Thread');
        $user = $this->signIn();
        $thread->subscribe();
        // $user->subscriptions()->where('thread_id', $thread->id)->get();
        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', auth()->id())->count()
        );
    }

    public function test_user_can_unsubscribe_from_thread()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $thread->subscribe();
        $thread->unsubscribe();
        $this->assertCount(0, $thread->subscriptions);
    }

    public function test_check_user_subscribe_to_thread()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribed);
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribed);
    }

    public function test_auth_user_read_thread_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));
            $user->readThread($thread);
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }
}
