@extends('sendportal::layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header card-header-accent">
                    <div class="card-header-inner">
                        {{ __('Edit Profile') }}
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('profile.update') }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input type="text" id="name" class="form-control" name="name"
                                       value="{{ old('name') || $errors->has('name') ? old('name') : auth()->user()->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input type="email" id="email" class="form-control" name="email"
                                       value="{{ old('email') || $errors->has('email') ? old('email') : auth()->user()->email }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ __('Locale') }}</label>

                            <div class="col-md-6">
                                <select name="locale" id="locale" class="form-control">
                                    @foreach(config()->get('sendportal-host.locale.supported') as $key => $locale)
                                        <option value="{{ $key }}" {{ auth()->user()->locale === $key ? 'selected="selected"' : '' }}">
                                            {{ $locale['name'] }}
                                        </option>
                                    @endforeach
                                </select>
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
