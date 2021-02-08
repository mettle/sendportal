@extends('sendportal::layouts.base')

@section('title', 'Application Setup')

@push('css')
    @livewireStyles
@endpush

@section('htmlBody')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('auth.partials.logo')

                <livewire:setup />
            </div>
        </div>
    </div>

@endsection

@push('js')
    @livewireScripts
@endpush