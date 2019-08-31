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
        $this->signIn()
            ->post('/threads', $attributes = raw(Thread::class))
            ->followRedirects()
            ->assertSee($attributes['title'])
            ->assertSee($attributes['body']);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 99999])
            ->assertSessionHasErrors('channel_id');
    }

    private function publishThread($overrides)
    {
        return $this->withExceptionHandling()
            ->signIn()
            ->post('/threads', raw(Thread::class, $overrides));
    }
}
