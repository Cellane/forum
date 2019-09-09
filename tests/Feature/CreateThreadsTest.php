<?php

namespace Tests\Feature;

use App\Activity;
use App\Reply;
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
    public function authenticated_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->publishThread()
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_form_threads()
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

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create(Thread::class);

        $this->withExceptionHandling()
            ->delete($thread->path())
            ->assertRedirect('login');

        $this->signIn()
            ->delete($thread->path())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->delete($thread->path())
            ->assertStatus(302);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

    private function publishThread($overrides = [])
    {
        return $this->withExceptionHandling()
            ->signIn()
            ->post('/threads', raw(Thread::class, $overrides));
    }
}
