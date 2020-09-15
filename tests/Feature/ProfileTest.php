<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_has_profile()
    {
        $user = create('App\User');
        $this->get("/profile/{$user->name}")
            ->assertSee($user->name);
    }
    public function test_user_profile_has_thread()
    {
        $this->signIn();
        $user = create('App\User');
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->get("/profile/" . auth()->user()->name)
            ->assertSee($thread->title);
    }
}
