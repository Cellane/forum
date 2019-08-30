<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_create_threads()
    {
        $this->expectException(AuthenticationException::class);

        $attributes = raw(Thread::class);

        $this->post('/threads', $attributes);
    }

    /** @test */
    public function an_authenticated_user_can_crate_new_form_threads()
    {
        $this->signIn();

        $attributes = raw(Thread::class);

        $this->post('/threads', $attributes)
            ->followRedirects()
            ->assertSee($attributes['title'])
            ->assertSee($attributes['body']);
    }
}
