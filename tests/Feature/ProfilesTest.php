<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create(User::class);

        $this->get(route('profile', $user))
            ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->get(route('profile', $user))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
