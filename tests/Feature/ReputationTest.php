<?php

namespace Tests\Feature;

use App\Reputation;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_earns_points_when_they_create_a_thread()
    {
        $thread = create(Thread::class);

        $this->assertEquals(Reputation::THREAD_PUBLISHED, $thread->creator->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_they_reply_to_a_thread()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Here is a reply'
        ]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_marked_as_best()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Here is a reply'
        ]);
        $reputation = Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED;

        $thread->markBestReply($reply);

        $this->assertEquals($reputation, $reply->owner->reputation);
    }
}
