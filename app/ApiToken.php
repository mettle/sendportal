<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Crypt;
use Sendportal\Base\Models\BaseModel;

class ApiToken extends BaseModel
{

    /**
     * @var array
     */
    protected $casts = [
        'api_token' => ['encrypted']
    ];

    public static function resolveWorkspaceId($rawHeaderValue)
    {
    //    Crypt::dec
    }
}