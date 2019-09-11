<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', $replies[1]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_can_mark_a_reply_as_best()
    {
        $thread = create(Thread::class, ['user_id' => create(User::class)->id]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->withExceptionHandling()
            ->signIn()
            ->postJson(route('best-replies.store', $replies[1]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = create(Reply::class, [
            'thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);

        $thread->markBestReply($reply);
        $this->delete(route('replies.destroy', $reply));

        $this->assertNull($thread->fresh()->best_reply_id);
    }
}
