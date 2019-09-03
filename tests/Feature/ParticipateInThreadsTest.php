<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $thread = create(Thread::class);

        $this->withExceptionHandling()
            ->post($thread->path() . '/replies', raw(Reply::class))
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $thread = create(Thread::class);

        $this->signIn()
            ->post($thread->path() . '/replies', $attributes = raw(Reply::class));

        $this->get($thread->path())
            ->assertSee($attributes['body']);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $thread = create(Thread::class);

        $this->withExceptionHandling()
            ->signIn()
            ->post($thread->path() . '/replies', raw(Reply::class, ['body' => null]))
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $reply = create(Reply::class);

        $this->withExceptionHandling()
            ->delete($reply->resourcePath())
            ->assertRedirect('login');
        $this->signIn()
            ->delete($reply->resourcePath())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->delete($reply->resourcePath())
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);
    }
}
