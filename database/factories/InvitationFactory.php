<?php

/** @var Factory $factory */

use App\Invitation;
use App\Workspace;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

$factory->define(Invitation::class, static function (Faker $faker) {
    return [
        'id' => Uuid::uuid4(),
        'user_id' => null,
        'workspace_id' => factory(Workspace::class),
        'role' => Workspace::ROLE_MEMBER,
        'email' => $faker->safeEmail,
        'token' => Str::random(40)
    ];
});
