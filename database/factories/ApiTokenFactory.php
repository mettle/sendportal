<?php

/** @var Factory $factory */

use App\ApiToken;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ApiToken::class, static function (Faker $faker) {
    return [
        'api_token' => Str::random(32),
    ];
});