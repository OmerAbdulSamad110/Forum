<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;
    public function test_mentioned_user_in_reply_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);
        $this->signIn($john);
        $jane = create('App\User', ['name' => 'JaneDoe']);
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => '@JaneDoe look at this.'
        ]);
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->assertCount(1, $jane->notifications);
    }

    public function test_mentioned_user_wrap_in_anchor()
    {
        $reply = make('App\Reply', [
            'body' => '@JaneDoe, look at this.'
        ]);
        $this->assertEquals('<a href="/profile/JaneDoe">@JaneDoe</a>, look at this.', $reply->body);
    }
}
