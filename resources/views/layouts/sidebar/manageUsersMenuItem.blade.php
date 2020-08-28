@if ( auth()->user()->ownsCurrentWorkspace())
    <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('sendportal.users.index') }}">
            <i class="fas fa-users mr-2"></i><span>{{ __('Manage Users') }}</span>
        </a>
    </li>
@endif