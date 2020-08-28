@extends('sendportal::layouts.app')

@section('heading')
    {{ __('Workspaces') }}
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <div class="card">
                <div class="card-header">
                    {{ __('Current Workspaces') }}
                </div>
                <div class="card-table">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>
                                <div class="pl-2">
                                    {{ __('Name') }}
                                </div>
                            </th>
                            <th width="30%">{{ __('Owner') }}</th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($workspaces as $workspace)
                            <tr>
                                <td>
                                    <div class="pl-2">
                                        {{ $workspace->name }}
                                    </div>
                                </td>
                                <td>
                                    @if (auth()->user()->id === $workspace->owner_id)
                                        You
                                    @else
                                        {{ $workspace->owner->name }}
                                    @endif
                                </td>
                                <td class="td-fit">
                                    @if (auth()->user()->ownsWorkspace($workspace))
                                        <a href="{{route('workspaces.edit', $workspace->id)}}" class="btn btn-light btn-sm">{{ __('Edit') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if (count($invitations))
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-header-inner">
                            {{ __('Invitations') }}
                        </div>
                    </div>

                    <div class="card-table">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Workspace') }}</th>
                                <th width="15%">{{ __('Invited') }}</th>
                                <th width="15%">{{ __('Expires') }}</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->workspace->name }}</td>
                                    <td>{{ $invitation->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="{{ $invitation->isExpired() ? 'text-danger' : null }}">
                                            {{ $invitation->expires_at->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td class="td-fit">
                                        {{-- Accept --}}
                                        <form action="{{ route('workspaces.invitations.accept', $invitation) }}"
                                              method="post" style="display:inline-block;">
                                            @csrf
                                            <input
                                                type="submit"
                                                value="{{ __('Accept') }}"
                                                class="btn btn-sm btn-light"
                                                @if ($invitation->isExpired())
                                                disabled
                                                title="{{ __('This invitation has expired.') }}"
                                                @endif
                                            >
                                        </form>
                                        {{-- Reject --}}
                                        <form action="{{ route('workspaces.invitations.reject', $invitation) }}"
                                              method="post" style="display:inline-block;">
                                            @csrf
                                            <input type="submit" value="{{ __('Reject') }}"
                                                   class="btn btn-sm btn-light">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-header">
                    <div class="card-header-inner">
                        {{ __('Add Workspace') }}
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('workspaces.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label for="create-workspace-name"
                                   class="col-md-2 col-form-label">{{ __('Workspace Name') }}</label>

                            <div class="col-md-6">
                                <input type="text" id="create-workspace-name" class="form-control" name="name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-2">
                                <input type="submit" class="btn btn-md btn-primary" value="{{ __('Save') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
