<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    public function test_record_activity_thread_is_created()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'subjectable_id' => $thread->id,
            'subjectable_type' => 'App\Thread',
            'type' => 'created_thread'
        ]);
        $activity = Activity::first();
        $this->assertEquals($activity->subjectable->id, $thread->id);
    }

    public function test_record_activity_reply_is_created()
    {
        $this->signIn();
        create('App\Reply');
        $this->assertEquals(2, Activity::count());
    }

    public function test_fetches_users_feed()
    {
        $this->signIn();

        create('App\Thread', ['user_id' => auth()->id()], 2);
        $thread = auth()->user()->activities()->first();
        $thread->created_at = Carbon::now()->subWeek();
        $thread->save();
        $feed = Activity::feed(auth()->user());
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('d-m-y')
        ));
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('d-m-y')
        ));
    }
}
