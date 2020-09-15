<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_notify_thread_subcribers_on_reply_by_others()
    {
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some body'
        ]);
        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some body'
        ]);
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    function test_user_can_mark_notificatios_as_read()
    {
        create(DatabaseNotification::class);
        $this->assertCount(1, auth()->user()->unreadNotifications);
        $this->get("/notifications/" . auth()->user()->unreadNotifications->first()->id . "/read");
        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }

    public function test_fetch_user_notificatios()
    {
        create(DatabaseNotification::class);
        $this->assertCount(
            1,
            $this->getJson("/notifications")->json()
        );
    }
}
