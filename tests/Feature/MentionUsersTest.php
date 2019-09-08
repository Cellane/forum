<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create(User::class, ['name' => 'JohnDoe']);
        $jane = create(User::class, ['name' => 'JaneDoe']);
        $thread = create(Thread::class);
        $reply = raw(Reply::class, ['body' => '@JaneDoe look at this. Also @FrankDoe']);

        $this->signIn($john)
            ->json('post', $thread->path() . '/replies', $reply);

        $this->assertCount(1, $jane->notifications);
    }
}
