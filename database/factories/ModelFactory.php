<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Faker\Generator;

$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Thread::class, function (Generator $faker) {
    return [
        'user_id' => factory(User::class),
        'channel_id' => factory(Channel::class),
        'title' => $faker->sentence,
        'body' => $faker->paragraph
    ];
});

$factory->define(Reply::class, function (Generator $faker) {
    return [
        'thread_id' => factory(Thread::class),
        'user_id' => factory(User::class),
        'body' => $faker->paragraph()
    ];
});

$factory->define(Channel::class, function (Generator $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name
    ];
});
