<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Sendportal\Base\Models\BaseModel;

/**
 * @property int $id
 * @property string $api_token
 * @property string $description
 * @property int $workspace_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ApiToken extends BaseModel
{
    protected $guarded = [];

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
    public static function resolveWorkspaceId($rawHeaderValue): ?int
    {
        $apiTokenInstance = self::where('api_token', $rawHeaderValue)->first();

        return $apiTokenInstance->workspace_id ?? null;
    }
}
