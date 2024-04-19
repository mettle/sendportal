<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ApiTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sendportal\Base\Models\BaseModel;

/**
 * @property int $id
 * @property string $api_token
 * @property string $description
 * @property int $workspace_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ApiTokenFactory factory
 */
class ApiToken extends BaseModel
{
    use HasFactory;

    /** @var array */
    protected $guarded = [];

    /**
     * @var array
     */
    protected function casts(): array
    {
        return [
            'workspace_id' => 'integer',
        ];
    }

    public static function resolveWorkspaceId($rawHeaderValue): ?int
    {
        $apiTokenInstance = self::where('api_token', $rawHeaderValue)->first();

        return $apiTokenInstance->workspace_id ?? null;
    }
}
