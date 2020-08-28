<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    public $incrementing = false;

    protected $guarded = [];

    public function getExpiresAtAttribute(): Carbon
    {
        return $this->created_at->addWeek();
    }

    /**
     * The workspace this invitation is for.
     *
     * @return BelongsTo
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->gte($this->expires_at);
    }

    public function isNotExpired(): bool
    {
        return !$this->isExpired();
    }
}
