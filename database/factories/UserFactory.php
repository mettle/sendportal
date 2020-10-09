<?php

/** @var Factory $factory */

use App\User;
use App\Workspace;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, static function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'api_token' => Str::random(80),
    ];
});

$factory->afterCreatingState(User::class, 'workspace-member', static function (User $user) {
    /** @var Workspace $workspace */
    $workspace = factory(Workspace::class)->create();
    $workspace->users()->attach($user, ['role' => Workspace::ROLE_MEMBER]);
});
