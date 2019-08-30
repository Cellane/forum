<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException(AuthenticationException::class);

        $thread = create(Thread::class);
        $reply = raw(Reply::class);

        $this->post($thread->path() . '/replies', $reply);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = raw(Reply::class);

        $this->post($thread->path() . '/replies', $reply);

        $this->get($thread->path())
            ->assertSee($reply['body']);
    }
}
