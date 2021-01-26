<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApiToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApiTokenFactory extends Factory
{
    /** @var string */
    protected $model = ApiToken::class;

    public function definition(): array
    {
        return [
            'api_token' => Str::random(32),
        ];
    }
}