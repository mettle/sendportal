<?php

declare(strict_types=1);

namespace App\Repositories;

use App\ApiToken;
use Sendportal\Base\Repositories\BaseEloquentRepository;

class ApiTokenRepository extends BaseEloquentRepository
{
    /** @var string */
    protected $modelName = ApiToken::class;
}