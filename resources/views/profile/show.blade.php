@extends('sendportal::layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header card-header-accent">
                    <div class="card-header-inner">
                        {{ __('Profile') }}
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="name" value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="email" value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="locale" class="col-sm-2 col-form-label">{{ __('Locale') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="locale" value="{{ auth()->user()->locale }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="api-key" class="col-sm-2 col-form-label">{{ __('API Token') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="api-key" value="{{ auth()->user()->api_token }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-md btn-primary">{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
