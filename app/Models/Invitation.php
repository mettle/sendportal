<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $workspace_id
 * @property int|null $user_id
 * @property string|null $role
 * @property string $email
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Workspace $workspace
 *
 * @property-read Carbon $expires_at
 *
 * @method static InvitationFactory factory
 */
class Invitation extends Model
{
    use HasFactory;

    /** @var bool */
    public $incrementing = false;

    /** @var array */
    protected $guarded = [];

    /**
     * The workspace this invitation is for.
     *
     * @return BelongsTo
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function getExpiresAtAttribute(): Carbon
    {
        return $this->created_at->addWeek();
    }

    public function isExpired(): bool
    {
        return Carbon::now()->gte($this->expires_at);
    }

    public function isNotExpired(): bool
    {
        return ! $this->isExpired();
    }
}
