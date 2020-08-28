<?php

declare(strict_types=1);

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    public const ROLE_OWNER = 'owner';
    public const ROLE_MEMBER = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'owner_id' => 'int',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the owner of the workspace.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all of the users that belong to the workspace.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_users')
            ->orderBy('name')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all of the workspace's invitations.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * The subscriptions that belong to this workspace.
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'workspace_id')->orderBy('created_at', 'desc');
    }

    /**
     * Make the workspace attributes visible for an owner.
     *
     * @return void
     */
    public function shouldHaveOwnerVisibility(): void
    {
        $this->makeVisible([
            'card_brand',
            'card_last_four',
            'card_country',
            'billing_address',
            'billing_address_line_2',
            'billing_city',
            'billing_state',
            'billing_zip',
            'billing_country',
            'extra_billing_information',
        ]);
    }

    /**
     * Detach all of the users from the workspace and delete the workspace.
     *
     * @return void
     * @throws Exception
     */
    public function detachUsersAndDestroy(): void
    {
        $this->users()
            ->where('current_workspace_id', $this->id)
            ->update(['current_workspace_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
