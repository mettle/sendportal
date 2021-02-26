@extends('sendportal::layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header card-header-accent">
                    <div class="card-header-inner">
                        {{ __('Update Password') }}
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('profile.password.update') }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ __('Current Password') }}</label>

                            <div class="col-md-6">
                                <input type="password" id="current_password" class="form-control" name="current_password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ __('Confirm New Password') }}</label>

                            <div class="col-md-6">
                                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
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
