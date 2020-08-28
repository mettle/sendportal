@extends('sendportal::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-inner">
                        {{ __('Verify Your Email Address') }}
                    </div>
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                        <a href="{{ route('verification.resend') }}"
                           onclick="event.preventDefault(); document.getElementById('verification-resend-form').submit();"
                        >{{ __('click here to request another') }}</a>.
                    <form id="verification-resend-form" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
