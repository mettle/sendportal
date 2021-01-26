<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\User;
use App\Rules\ValidInvitation;
use App\Services\Workspaces\AcceptInvitation;
use App\Services\Workspaces\CreateWorkspace;
use App\Traits\ChecksInvitations;

class RegisterController extends Controller
{
    use RegistersUsers,
        ChecksInvitations;

    /** @var AcceptInvitation */
    private $acceptInvitation;

    /** @var CreateWorkspace */
    private $createWorkspace;

    public function __construct(AcceptInvitation $acceptInvitation, CreateWorkspace $createWorkspace)
    {
        $this->middleware('guest');

        $this->acceptInvitation = $acceptInvitation;
        $this->createWorkspace = $createWorkspace;
    }

    /**
     * @return mixed
     */
    public function showRegistrationForm(Request $request)
    {
        $invitation = $request->get('invitation');

        if ($invitation && $this->isInvalidInvitation($invitation)) {
            return redirect('register')
                ->with('error', __('The invitation is no longer valid.'));
        }

        return view('auth.register');
    }

    protected function validator(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'/*, 'confirmed'*/],
            'invitation' => [new ValidInvitation()]
        ]);
    }

    protected function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            /** @var User $user */
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if ($token = request('invitation')) {
                // Attach user to invited workspace.
                $this->acceptInvitation->handle($user, $this->getInvitationFromToken($token));
            } else {
                // Create a new workspace and attach as owner.
                $this->createWorkspace->handle($user, $data['company_name'], Workspace::ROLE_OWNER);
            }

            return $user;
        });
    }

    protected function redirectTo(): string
    {
        return route('sendportal.dashboard');
    }
}
