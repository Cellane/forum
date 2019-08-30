<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
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

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->raw();

        $this->post($thread->path() . '/replies', $reply);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->raw();

        $this->post($thread->path() . '/replies', $reply);

        $this->get($thread->path())
            ->assertSee($reply['body']);
    }
}
