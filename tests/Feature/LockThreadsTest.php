<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $thread = create(Thread::class);

        $this->withExceptionHandling()
            ->signIn()
            ->post(route('locked-threads.store', $thread))
            ->assertStatus(403);
        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        $user = factory(User::class)->states('administrator')->create();
        $thread = create(Thread::class);

        $this->signIn($user)
            ->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_unlock_threads()
    {
        $user = factory(User::class)->states('administrator')->create();
        $thread = create(Thread::class, ['locked' => true]);

        $this->signIn($user)
            ->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $thread = create(Thread::class, ['locked' => true]);

        $this->signIn()
            ->post($thread->path() . '/replies', [
                'body' => 'Foobar',
                'user_id' => auth()->id()
            ])
            ->assertStatus(422);
    }
}
