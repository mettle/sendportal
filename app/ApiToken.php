<?php

declare(strict_types=1);

namespace App;

use Sendportal\Base\Models\BaseModel;

class ApiToken extends BaseModel
{

    /**
     * @var array
     */
    protected $casts = [
        'workspace_id' => 'integer'
    ];

    /**
     * @param $rawHeaderValue
     * @return int|null
     */
    public static function resolveWorkspaceId($rawHeaderValue):?int
    {
        $apiTokenInstance = self::where('api_token', $rawHeaderValue)->first();

        return $apiTokenInstance->workspace_id ?? null;
    }
}