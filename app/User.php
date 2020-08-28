<?php

namespace App;

use App\Traits\HasWorkspaces;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
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
        'api_token',
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
