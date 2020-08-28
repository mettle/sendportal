@extends('sendportal::layouts.app')

@section('heading')
    {{ __('Workspace Members') }}
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    {{ __('Current Users') }}
                </div>

                <div class="card-table table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucwords($user->pivot->role) }}</td>
                                <td>
                                    @if ($user->id === auth()->user()->id)
                                        <button
                                            class="btn btn-sm btn-light"
                                            disabled
                                            title="{{ __('You cannot remove yourself from the workspace.') }}"
                                        >
                                            Remove
                                        </button>
                                    @else
                                        <form action="{{ route('users.destroy', $user->id) }}"
                                              method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-sm btn-light"
                                                   value="{{ __('Remove') }}">
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ( auth()->user()->ownsCurrentWorkspace() && count($invitations) > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        {{ __('Invited Users') }}
                    </div>

                    <div class="card-table">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Expires') }}</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->email }}</td>
                                    <td>{{ $invitation->expires_at->format('Y-m-d') }}</td>
                                    <td class="td-fit">
                                        <form
                                            action="{{ route('users.invitations.destroy', $invitation) }}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                    class="btn btn-sm btn-light">{{ __('Retract') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ( auth()->user()->ownsCurrentWorkspace())
                <div class="card mt-3">
                    <div class="card-header">
                        {{ __('Invite User') }}
                    </div>
                    <div class="card-body">

                        @if(config('sendportal.auth.register'))

                            <form action="{{ route('users.invitations.store') }}" method="post">

                                @csrf
                                <div class="form-group row">
                                    <label for="create-invitation-email" class="col-sm-2">{{ __('Email Address') }}</label>

                                    <div class="col-sm-6">
                                        <input type="text" id="create-invitation-email" class="form-control" name="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <input type="submit" class="btn btn-md btn-primary" value="{{ __('Send Invite') }}">
                                    </div>
                                </div>
                            </form>

                        @else

                            <p class="empty-table-text">In order to invite users, you have to enable registration in the Sendportal configuration file.</p>

                        @endif

                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
