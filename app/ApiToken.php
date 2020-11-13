<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Sendportal\Base\Models\BaseModel;

/**
 * @property int $id
 * @property string $api_token
 * @property int $workspace_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ApiToken extends BaseModel
{
    protected $guarded = [];

    public function getApiTokenAttribute(string $value): string
    {
        return Crypt::decrypt($value);
    }

    public function setApiTokenAttribute(string $apiToken): void
    {
        $this->attributes['api_token'] = Crypt::encrypt($apiToken);
    }
}