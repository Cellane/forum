<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_administrator_can_lock_any_thread()
    {
        $thread = create(Thread::class);

        $thread->lock();

        $this->signIn()
            ->post($thread->path() . '/replies', [
                'body' => 'Foobar',
                'user_id' => auth()->id()
            ])
            ->assertStatus(422);
    }
}
