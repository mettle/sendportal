@extends('sendportal::layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                <div>

                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-md btn-primary">{{ __('Edit Profile') }}</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-accent">
                    <div class="card-header-inner">
                        {{ __('Profile') }}
                    </div>
                </div>
                <table class="table">
                    <tbody>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('Email') }}</th>
                        <td>{{ auth()->user()->email}}</td>
                    </tr>

                    <tr>
                        <th>{{ __('Locale') }}</th>
                        <td>{{ auth()->user()->locale }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="card-body">
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-md btn-light">{{ __('Change Password') }}</a>
                </div>
            </div>

        </div>
    </div>
@endsection
