<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Invitation;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

trait HasWorkspaces
{
    /** @var Workspace */
    protected $activeWorkspace;

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_users')
            ->orderBy('name', 'asc')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function hasWorkspaces(): bool
    {
        return $this->workspaces->count() > 0;
    }

    public function onWorkspace(Workspace $workspace): bool
    {
        return $this->workspaces->contains($workspace);
    }

    public function ownsWorkspace(Workspace $workspace): bool
    {
        return $this->id && $workspace->owner_id && (int) $this->id === (int) $workspace->owner_id;
    }

    public function currentWorkspaceId(): ?int
    {
        if ($this->activeWorkspace !== null) {
            return $this->activeWorkspace->id;
        }

        if ($this->current_workspace_id) {
            $this->switchToWorkspace(Workspace::find($this->current_workspace_id));

            return $this->activeWorkspace->id;
        }

        if ($this->activeWorkspace === null && $this->hasWorkspaces()) {
            $this->switchToWorkspace($this->workspaces()->first());

            return $this->activeWorkspace->id;
        }

        return null;
    }

    public function getCurrentWorkspaceAttribute(): ?Workspace
    {
        return $this->currentWorkspace();
    }

    public function ownsCurrentWorkspace(): bool
    {
        return $this->currentWorkspace() && (int) $this->currentWorkspace()->owner_id === (int) $this->id;
    }

    public function switchToWorkspace(Workspace $workspace): void
    {
        if (! $this->onWorkspace($workspace)) {
            throw new InvalidArgumentException('User does not belong to this workspace');
        }

        $this->activeWorkspace = $workspace;

        $this->current_workspace_id = $workspace->id;
        $this->save();
    }

    public function currentWorkspace(): ?Workspace
    {
        if ($this->activeWorkspace !== null) {
            return $this->activeWorkspace;
        }
        if ($this->current_workspace_id) {
            $this->switchToWorkspace(Workspace::find($this->current_workspace_id));

            return $this->activeWorkspace;
        }
        if ($this->activeWorkspace === null && $this->hasWorkspaces()) {
            $this->switchToWorkspace($this->workspaces()->first());

            return $this->activeWorkspace;
        }

        return null;
    }
}
