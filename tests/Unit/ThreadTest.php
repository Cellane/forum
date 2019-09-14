<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    public function it_has_a_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->slug}",
            $this->thread->path()
        );
    }

    /** @test */
    public function it_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function it_has_an_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function it_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => $this->thread->creator->id
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function it_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foober',
                'user_id' => create(User::class)->id
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function it_can_be_subscribed_to()
    {
        $this->thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $this->thread->subscriptions()->where(['user_id' => $userId])->count()
        );
    }

    /** @test */
    public function it_can_be_unsubscribed_from()
    {
        $this->thread->subscribe($userId = 1);
        $this->thread->unsubscribe($userId);

        $this->assertCount(0, $this->thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribedTo);
    }

    /** @test */
    public function it_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        tap(auth()->user(), function ($user) {
            $this->assertTrue($this->thread->hasUpdatesFor($user));

            $user->read($this->thread);

            $this->assertFalse($this->thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    public function it_records_a_new_visit_each_time_the_thread_is_read()
    {
        $this->assertEquals(0, $this->thread->visits);

        $this->get($this->thread->path());

        $this->assertEquals(1, $this->thread->fresh()->visits);
    }

    /** @test */
    public function it_sanitizes_body_automatically()
    {
        $thread = make(Thread::class, [
            'body' => '<script>alert("bad")</script><p>This is okay.</p>'
        ]);

        $this->assertEquals('<p>This is okay.</p>', $thread->body);
    }
}
