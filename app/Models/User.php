<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasWorkspaces;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property int|null $current_workspace_id
 * @property string|null $remember_token
 * @property string $locale
 * @property Carbon $created_at
 * @property Carbon updated_at
 *
 * @method static UserFactory factory
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasFactory;
    use HasWorkspaces;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'locale'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
    }

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_users')
            ->orderBy('name')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Return user avatar url
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=80&d=mm&r=g';
    }
}
