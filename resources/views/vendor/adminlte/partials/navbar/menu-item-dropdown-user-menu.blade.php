@php
    $user = Auth::user();
    $logoutUrl = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout');
    $logoutUrl = config('adminlte.use_route_url', false) ? route($logoutUrl) : url($logoutUrl);
    $initial = mb_strtoupper(mb_substr(trim($user->name ?? 'U'), 0, 1));
    $avatarUrl = $user->avatar_url;
    $role = ($user->type ?? null) === 'superadmin'
        ? 'Administrator'
        : ucfirst(str_replace('_', ' ', $user->type ?? 'Photographer'));
@endphp

<li class="nav-item dropdown user-menu pr-user-menu">
    <a href="#" class="nav-link dropdown-toggle top-user-toggle" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        @if($avatarUrl)
            <img class="top-user-avatar" src="{{ $avatarUrl }}" alt="">
        @else
            <span class="top-user-avatar" aria-hidden="true">{{ $initial }}</span>
        @endif
        <span class="top-user-copy">
            <strong>{{ $user->name }}</strong>
            <small>{{ $role }}</small>
        </span>
        <i class="fas fa-chevron-down top-user-chevron" aria-hidden="true"></i>
        <span class="sr-only">Open account menu</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right account-dropdown">
        <div class="account-summary">
            @if($avatarUrl)
                <img class="account-avatar" src="{{ $avatarUrl }}" alt="">
            @else
                <span class="account-avatar" aria-hidden="true">{{ $initial }}</span>
            @endif
            <div>
                <strong>{{ $user->name }}</strong>
                <small>{{ $user->email }}</small>
            </div>
        </div>

        <div class="account-actions">
            @if($user->photographer)
                <a href="{{ route('photographer.profile.settings') }}" class="account-action">
                    <span class="account-action-icon profile-icon"><i class="far fa-user"></i></span>
                    <span><strong>My profile</strong><small>Account details and password</small></span>
                    <i class="fas fa-chevron-right account-action-arrow"></i>
                </a>
            @endif

            <a href="#" class="account-action logout-action"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="account-action-icon logout-icon"><i class="fas fa-power-off"></i></span>
                <span><strong>Log out</strong><small>End your current session</small></span>
                <i class="fas fa-chevron-right account-action-arrow"></i>
            </a>
        </div>

        <form id="logout-form" action="{{ $logoutUrl }}" method="POST" class="d-none">
            @if(config('adminlte.logout_method'))
                {{ method_field(config('adminlte.logout_method')) }}
            @endif
            @csrf
        </form>
    </div>
</li>
