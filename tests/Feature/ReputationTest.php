<?php

namespace Tests\Feature;

use App\Reply;
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
    public function a_user_loses_points_when_they_delete_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->assertEquals(Reputation::THREAD_PUBLISHED, $thread->creator->reputation);

        $this->delete($thread->path());

        $this->assertEquals(0, $thread->creator->fresh()->reputation);
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
    public function a_user_loses_points_when_they_delete_a_reply()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);

        $this->delete(route('replies.destroy', $reply));

        $this->assertEquals(0, $reply->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_marked_as_best()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Here is a reply'
        ]);
        $total = Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED;

        $thread->markBestReply($reply);

        $this->assertEquals($total, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_favorited()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply'
        ]);
        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

        $this->signIn()
        ->post(route('favorite-replies.store', $reply));

        $this->assertEquals($total, $reply->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_reply_is_unfavorited()
    {
        $reply = create(Reply::class, ['user_id' => create(User::class)->id]);
        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

        $this->signIn()
        ->post(route('favorite-replies.store', $reply));

        $this->assertEquals($total, $reply->owner->fresh()->reputation);

        $this->delete(route('favorite-replies.destroy', $reply));

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->fresh()->reputation);
    }
}
