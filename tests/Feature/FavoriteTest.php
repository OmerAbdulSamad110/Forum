<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_cannot_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post("/replies/1/favorites")
            ->assertRedirect('/login');
    }

    public function test_auth_user_favorite_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->post("/replies/{$reply->id}/favorites");
        $this->assertCount(1, $reply->favorites);
    }

    public function test_auth_user_unfavorite_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->post("/replies/{$reply->id}/favorites");

        $this->delete("/replies/{$reply->id}/favorites");
        $this->assertCount(0, $reply->favorites);
    }
}
