<?php

namespace Tests\Feature;

use App\Activity;
use App\Reply;
use App\Rules\Recaptcha;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    use MockeryPHPUnitIntegration;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function unauthenticated_users_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect(route('login'));
        $this->post(route('threads'), raw(Thread::class))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory(User::class)->states('unconfirmed')->create();
        $thread = raw(Thread::class);

        $this->signIn($user);

        $this->post(route('threads'), $thread)
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    public function a_user_can_create_new_form_threads()
    {
        $this->publishThread($attributes = ['title' => 'Some Title', 'body' => 'Some body.'])
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
    public function a_thread_requires_captcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
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
    public function a_thread_requires_a_unique_slug()
    {
        $thread = create(Thread::class, ['title' => 'Foo Title']);

        $this->assertEquals($thread->slug, 'foo-title');

        $thread = $this->signIn()
            ->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $thread = create(Thread::class, ['title' => 'Some Title 42']);

        $thread = $this->signIn()
            ->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("some-title-42-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create(Thread::class);

        $this->withExceptionHandling()
            ->delete($thread->path())
            ->assertRedirect(route('login'));

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
        $thread = raw(Thread::class, $overrides);

        return $this->withExceptionHandling()
            ->signIn()
            ->post(route('threads'), $thread + ['g-recaptcha-response' => 'token']);
    }
}
