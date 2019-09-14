<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function all_users_starting_with_the_given_characters_can_be_fetched()
    {
        create(User::class, ['name' => 'johndoe']);
        create(User::class, ['name' => 'johndoe2']);
        create(User::class, ['name' => 'janedoe']);

        $results = $this->json('get', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }
}
