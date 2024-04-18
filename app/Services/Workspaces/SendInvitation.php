<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Workspace;
use Exception;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class SendInvitation
{
    /**
     * @throws Exception
     */
    public function handle(Workspace $workspace, string $email): Invitation
    {
        $existingUser = User::where('email', $email)->first();

        $invitation = $this->createInvitation($workspace, $email, Workspace::ROLE_MEMBER, $existingUser);

        if ($existingUser) {
            // If there is an existing user, we are just going to automatically accept the invitation. This avoids
            // needing to support displaying workspace invitation management to users who are not part of any existing
            // workspace, but also just makes sense, as it isn't likely that anyone already signed up to a SendPortal
            // instance would really want to reject an invitation in the first place, so we remove friction here.
            /** @var AcceptInvitation $acceptInvitation */
            $acceptInvitation = app(AcceptInvitation::class);
            $acceptInvitation->handle($existingUser, $invitation);
        }

        $this->emailInvitation($invitation);

        return $invitation;
    }

    protected function emailInvitation(Invitation $invitation): void
    {
        Mail::send(
            $this->getInvitationViewName($invitation),
            compact('invitation'),
            static function (Message $m) use ($invitation) {
                $m->to($invitation->email)->subject(__('New Invitation!'));
            }
        );
    }

    /**
     * @throws Exception
     */
    protected function createInvitation(Workspace $workspace, string $email, string $role, ?User $existingUser = null): Invitation
    {
        $invitationData = [
            'id' => Uuid::uuid4(),
            'user_id' => $existingUser->id ?? null,
            'role' => $role,
            'email' => $email,
            'token' => Str::random(40),
        ];

        return $workspace->invitations()->create($invitationData);
    }

    protected function getInvitationViewName(Invitation $invitation): string
    {
        return $invitation->user_id
            ? 'workspaces.emails.invitation-to-existing-user'
            : 'workspaces.emails.invitation-to-new-user';
    }
}
