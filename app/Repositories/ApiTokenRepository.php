<?php

declare(strict_types=1);

namespace App\Repositories;

use App\ApiToken;
use Sendportal\Base\Repositories\BaseTenantRepository;

class ApiTokenRepository extends BaseTenantRepository
{
    /** @var string */
    protected $modelName = ApiToken::class;
}
