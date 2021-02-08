@extends('sendportal::layouts.app')

@section('heading', __('Edit Workspace'))

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <div class="card">
                <div class="card-header">
                    {{ __('Update Workspace Name') }}
                </div>

                <div class="card-body">
                    <form action="{{ route('workspaces.update', $workspace) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="workspace_name"
                                   class="col-md-2 col-form-label">{{ __('Workspace Name') }}</label>

                            <div class="col-md-6">
                                <input type="text" id="edit-workspace-name" class="form-control" name="workspace_name"
                                       value="{{ $workspace->name }}">
                            </div>
                        </div>

                        <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Save') }}">
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
