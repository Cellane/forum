<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('login');
        $this->post('/threads', raw(Thread::class))
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_crate_new_form_threads()
    {
        $this->signIn();

        $this->post('/threads', $attributes = raw(Thread::class))
            ->followRedirects()
            ->assertSee($attributes['title'])
            ->assertSee($attributes['body']);
    }
}
