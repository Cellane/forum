<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->withExceptionHandling()
            ->json('post', '/api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()
            ->signIn()
            ->json('post', '/api/users/' . auth()->id() . '/avatar', [
                'avatar' => 'not-an-image'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        Storage::fake('public');

        $this->signIn()
            ->json('post', '/api/users/' . auth()->id() . '/avatar', [
                'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
            ]);

        $this->assertEquals(asset("avatars/{$file->hashName()}"), auth()->user()->avatar_path);
        Storage::disk('public')->assertExists("avatars/{$file->hashName()}");
    }
}
