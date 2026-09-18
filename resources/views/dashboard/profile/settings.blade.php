@extends('adminlte::page')
@section('title', 'Profile')

@php
    $photographer = $user->photographer;
    $nameParts = preg_split('/\s+/', trim($user->name ?? 'User'));
    $initials = collect($nameParts)->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
    $planStorageBytes = max(0, (float) ($photographer->plan_storage ?? 0));
    $availableStorageBytes = max(0, (float) ($photographer->available_storage ?? 0));
    $avatarUrl = $user->avatar_url;
    $usedStoragePercentage = $planStorageBytes > 0
        ? max(0, min(100, (int) round((($planStorageBytes - $availableStorageBytes) / $planStorageBytes) * 100)))
        : 0;
@endphp

@section('content')
<x-page-header title="Profile" description="Manage your personal information, password, and account overview."
    :breadcrumbs="[['label' => 'Profile']]" />

<div class="profile-settings-layout">
    <aside class="profile-overview-card">
        <div class="profile-cover">
            <span class="profile-cover__shape profile-cover__shape--one"></span>
            <span class="profile-cover__shape profile-cover__shape--two"></span>
        </div>

        <div class="profile-identity">
            <div class="profile-avatar-wrap">
                @if($avatarUrl)
                    <img class="profile-avatar" id="profile-avatar-preview" src="{{ $avatarUrl }}" alt="{{ $user->name }} profile picture">
                    <span class="profile-avatar profile-avatar--initials d-none" id="profile-avatar-fallback" aria-hidden="true">{{ $initials ?: 'U' }}</span>
                @else
                    <img class="profile-avatar d-none" id="profile-avatar-preview" src="" alt="{{ $user->name }} profile picture">
                    <span class="profile-avatar profile-avatar--initials" id="profile-avatar-fallback" aria-hidden="true">{{ $initials ?: 'U' }}</span>
                @endif
                <span class="profile-avatar-status" title="Active account"><i class="fas fa-check"></i></span>
                <label class="profile-avatar-edit" for="profile-avatar" title="Change profile picture" aria-label="Change profile picture"><i class="fas fa-camera"></i></label>
                <input class="sr-only" id="profile-avatar" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" form="profile-settings-form">
            </div>
            <label class="profile-change-photo" for="profile-avatar"><i class="fas fa-camera"></i> Change photo</label>
            @error('avatar')<small class="text-danger d-block profile-avatar-error">{{ $message }}</small>@enderror
            <span class="profile-role-badge"><i class="fas fa-camera"></i> Photographer</span>
            <h2>{{ $user->name ?? '' }}</h2>
            <p>{{ $user->email ?? '' }}</p>
            @if($user->created_at)
                <small><i class="far fa-calendar-alt"></i> Member since {{ $user->created_at->format('M Y') }}</small>
            @endif
        </div>

        <div class="profile-metrics">
            <div class="profile-metric profile-metric--clients">
                <span><i class="fas fa-users"></i></span>
                <strong>{{ $data['client_count'] }}</strong>
                <small>Clients</small>
            </div>
            <div class="profile-metric profile-metric--galleries">
                <span><i class="fas fa-images"></i></span>
                <strong>{{ $data['gallery_count'] }}</strong>
                <small>Galleries</small>
            </div>
            <div class="profile-metric profile-metric--sessions">
                <span><i class="fas fa-camera-retro"></i></span>
                <strong>{{ $data['session_count'] }}</strong>
                <small>Sessions</small>
            </div>
        </div>

        <div class="profile-storage">
            <div class="profile-storage__heading">
                <span><i class="fas fa-cloud"></i> Storage</span>
                <strong>{{ $usedStoragePercentage }}% used</strong>
            </div>
            <div class="profile-storage__track" role="progressbar" aria-label="Storage used" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $usedStoragePercentage }}">
                <span style="width: {{ $usedStoragePercentage }}%"></span>
            </div>
            <div class="profile-storage__values">
                <span>{{ $data['available_storage'] }} available</span>
                <span>{{ $data['plan_storage'] }} total</span>
            </div>
        </div>

        <a href="{{ route('dashboard.portfolio.edit') }}" class="profile-portfolio-link">
            <span><i class="fas fa-briefcase"></i> Manage portfolio</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    </aside>

    <section class="profile-form-card">
        <div class="profile-form-header">
            <div class="profile-form-header__icon"><i class="fas fa-user-cog"></i></div>
            <div>
                <span class="profile-section-eyebrow">Account settings</span>
                <h2>Personal information</h2>
                <p>Keep your account details accurate and your password secure.</p>
            </div>
        </div>

        <form id="profile-settings-form" method="POST" action="{{ route('photographer.profile.update') }}" enctype="multipart/form-data" autocomplete="off">
            @method('PUT')
            @csrf

            <div class="profile-form-section">
                <div class="profile-form-section__heading">
                    <span class="profile-form-section__number">01</span>
                    <div><h3>Your details</h3><p>The information used for your PhotoRent account.</p></div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="profile-name">Full name <span class="required_start">*</span></label>
                        <div class="profile-input-wrap">
                            <i class="far fa-user"></i>
                            <input id="profile-name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required autocomplete="username">
                        </div>
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="profile-email">Email address <span class="required_start">*</span></label>
                        <div class="profile-input-wrap">
                            <i class="far fa-envelope"></i>
                            <input id="profile-email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="email">
                        </div>
                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <div class="profile-form-section profile-form-section--security">
                <div class="profile-form-section__heading">
                    <span class="profile-form-section__number"><i class="fas fa-lock"></i></span>
                    <div><h3>Password &amp; security</h3><p>Leave these fields empty if you do not want to change your password.</p></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="current-password">Current password</label>
                    <div class="profile-input-wrap profile-input-wrap--password">
                        <i class="fas fa-shield-alt"></i>
                        <input id="current-password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                        <button class="password-toggle" type="button" data-password-toggle="current-password" aria-label="Show current password"><i class="far fa-eye"></i></button>
                    </div>
                    <small class="profile-field-help"><i class="fas fa-info-circle"></i> Required when changing your email address or password.</small>
                    @error('current_password')<small class="text-danger d-block">{{ $message }}</small>@enderror
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="new-password">New password</label>
                        <div class="profile-input-wrap profile-input-wrap--password">
                            <i class="fas fa-key"></i>
                            <input id="new-password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            <button class="password-toggle" type="button" data-password-toggle="new-password" aria-label="Show new password"><i class="far fa-eye"></i></button>
                        </div>
                        @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="password-confirmation">Confirm new password</label>
                        <div class="profile-input-wrap profile-input-wrap--password">
                            <i class="fas fa-check-circle"></i>
                            <input id="password-confirmation" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="new-password">
                            <button class="password-toggle" type="button" data-password-toggle="password-confirmation" aria-label="Show password confirmation"><i class="far fa-eye"></i></button>
                        </div>
                        @error('password_confirmation')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <div class="profile-form-footer">
                <span><i class="fas fa-shield-alt"></i> Your account information is kept private.</span>
                <button class="btn profile-save-button" type="submit"><i class="fas fa-save"></i> Save changes</button>
            </div>
        </form>
    </section>
</div>
@stop

@section('css')
<style>
    .profile-settings-layout { display: grid; grid-template-columns: minmax(285px, 330px) minmax(0, 1fr); align-items: start; gap: 1.25rem; padding: 0 .4rem 2rem; }
    .profile-overview-card, .profile-form-card { overflow: hidden; border: 1px solid #dfe8e7; border-radius: 1rem; background: #fff; box-shadow: 0 11px 30px rgba(31, 68, 74, .075); }
    .profile-cover { position: relative; height: 112px; overflow: hidden; background: linear-gradient(135deg, #073b74 0%, #1769c2 65%, #4b9bea 100%); }
    .profile-cover::after { position: absolute; right: -45px; bottom: -70px; width: 160px; height: 160px; border: 1px solid rgba(255,255,255,.17); border-radius: 50%; content: ''; }
    .profile-cover__shape { position: absolute; border-radius: 50%; background: rgba(255,255,255,.1); }
    .profile-cover__shape--one { top: -35px; left: -20px; width: 105px; height: 105px; }
    .profile-cover__shape--two { top: 22px; right: 52px; width: 22px; height: 22px; }
    .profile-identity { padding: 0 1.25rem 1rem; text-align: center; }
    .profile-avatar-wrap { position: relative; width: 94px; height: 94px; margin: -48px auto .65rem; }
    .profile-avatar { display: block; width: 94px; height: 94px; border: 5px solid #fff; border-radius: 50%; object-fit: cover; box-shadow: 0 8px 20px rgba(18, 54, 72, .18); }
    .profile-avatar--initials { display: grid; place-items: center; background: linear-gradient(145deg, #eaf4ff, #fff); color: #1769c2; font-size: 1.75rem; font-weight: 800; letter-spacing: -.04em; }
    .profile-avatar-status { position: absolute; right: 2px; bottom: 5px; display: grid; width: 24px; height: 24px; place-items: center; border: 3px solid #fff; border-radius: 50%; background: #20b86a; color: #fff; font-size: .55rem; }
    .profile-avatar-edit { position: absolute; right: -8px; top: 5px; z-index: 3; display: grid; width: 29px; height: 29px; margin: 0; place-items: center; border: 3px solid #fff; border-radius: 50%; background: #1769c2; box-shadow: 0 4px 10px rgba(16,73,128,.25); color: #fff; font-size: .62rem; cursor: pointer; transition: .2s ease; }
    .profile-avatar-edit:hover { background: #0f559f; transform: scale(1.07); }
    .profile-avatar-edit i{ color: #fff; }
    .profile-change-photo { display: inline-block; margin: 0 0 .65rem; color: #397fba; font-size: .67rem; font-weight: 750; cursor: pointer; }
    .profile-change-photo i { margin-right: .25rem; }
    .profile-avatar-error { margin: -.35rem 0 .65rem; }
    .profile-role-badge { display: inline-block; margin-bottom: .5rem; padding: .3rem .55rem; border-radius: 999px; background: #eaf3fc; color: #3378b8; font-size: .64rem; font-weight: 750; letter-spacing: .04em; text-transform: uppercase; }
    .profile-role-badge i { margin-right: .25rem; }
    .profile-identity h2 { margin: 0; color: #17384b; font-size: 1.18rem; font-weight: 750; }
    .profile-identity p { overflow: hidden; margin: .25rem 0 .45rem; color: #718695; font-size: .76rem; text-overflow: ellipsis; white-space: nowrap; }
    .profile-identity small { color: #91a0aa; font-size: .66rem; }
    .profile-identity small i { margin-right: .25rem; color: #5d8db7; }
    .profile-metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; padding: 1rem 1rem 1.1rem; border-top: 1px solid #e8efee; border-bottom: 1px solid #e8efee; background: #fbfdfd; }
    .profile-metric { min-width: 0; padding: .7rem .25rem; border: 1px solid #e6edec; border-radius: .7rem; background: #fff; text-align: center; }
    .profile-metric > span { display: grid; width: 28px; height: 28px; margin: 0 auto .4rem; place-items: center; border-radius: .5rem; background: #eaf3fc; color: #397fc3; font-size: .67rem; }
    .profile-metric--galleries > span { background: #f0edff; color: #7562cf; }
    .profile-metric--sessions > span { background: #e9f8f1; color: #16935f; }
    .profile-metric strong, .profile-metric small { display: block; }
    .profile-metric strong { color: #203c4d; font-size: 1.05rem; line-height: 1; }
    .profile-metric small { margin-top: .3rem; color: #83939c; font-size: .62rem; }
    .profile-storage { padding: 1.1rem 1.2rem; }
    .profile-storage__heading, .profile-storage__values { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
    .profile-storage__heading { color: #385567; font-size: .75rem; font-weight: 750; }
    .profile-storage__heading span i { margin-right: .3rem; color: #4b8ac4; }
    .profile-storage__heading strong { color: #748893; font-size: .65rem; }
    .profile-storage__track { height: 7px; overflow: hidden; margin: .7rem 0 .45rem; border-radius: 999px; background: #e8eff1; }
    .profile-storage__track span { display: block; min-width: 3px; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #2f80c9, #62a7f1); }
    .profile-storage__values { color: #8b9aa2; font-size: .61rem; }
    .profile-portfolio-link { display: flex; align-items: center; justify-content: space-between; margin: 0 1.2rem 1.2rem; padding: .72rem .8rem; border-radius: .65rem; background: #edf5fc; color: #286da9; font-size: .72rem; font-weight: 750; transition: .2s ease; }
    .profile-portfolio-link:hover { background: #dfedfa; color: #155e9c; text-decoration: none; transform: translateY(-1px); }
    .profile-portfolio-link span i { margin-right: .35rem; }
    .profile-form-header { display: flex; align-items: flex-start; gap: .85rem; padding: 1.35rem 1.5rem; border-bottom: 1px solid #e5edec; background: linear-gradient(135deg, #fbfdfd, #fff); }
    .profile-form-header__icon { display: grid; width: 43px; height: 43px; flex: 0 0 43px; place-items: center; border-radius: .75rem; background: linear-gradient(135deg, #1769c2, #4c96df); box-shadow: 0 7px 16px rgba(23, 105, 194, .2); color: #fff; }
    .profile-section-eyebrow { display: block; margin-bottom: .15rem; color: #5484ae; font-size: .63rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .profile-form-header h2 { margin: 0; color: #17384b; font-size: 1.12rem; font-weight: 750; }
    .profile-form-header p { margin: .28rem 0 0; color: #7f919b; font-size: .75rem; }
    .profile-form-card form { padding: 0 1.5rem; }
    .profile-form-section { padding: 1.35rem 0 .8rem; }
    .profile-form-section--security { border-top: 1px solid #e7eeed; }
    .profile-form-section__heading { display: flex; align-items: center; gap: .65rem; margin-bottom: 1.15rem; }
    .profile-form-section__number { display: grid; width: 31px; height: 31px; flex: 0 0 31px; place-items: center; border-radius: .55rem; background: #edf5fc; color: #367bb8; font-size: .66rem; font-weight: 800; }
    .profile-form-section__heading h3 { margin: 0; color: #284757; font-size: .88rem; font-weight: 750; }
    .profile-form-section__heading p { margin: .15rem 0 0; color: #91a0a7; font-size: .67rem; }
    .profile-form-card label { margin-bottom: .42rem; color: #385465; font-size: .74rem; font-weight: 700; }
    .profile-input-wrap { position: relative; }
    .profile-input-wrap > i { position: absolute; top: 50%; left: .85rem; z-index: 2; color: #7f9aab; font-size: .74rem; transform: translateY(-50%); pointer-events: none; }
    .profile-input-wrap .form-control { height: 43px; padding: .55rem .85rem .55rem 2.35rem; border-color: #d8e3e7; border-radius: .6rem; background: #fbfdfe; color: #294350; font-size: .82rem; transition: .2s ease; }
    .profile-input-wrap--password .form-control { padding-right: 2.65rem; }
    .profile-input-wrap .form-control:focus { border-color: #68a4db; background: #fff; box-shadow: 0 0 0 3px rgba(48, 128, 201, .1); }
    .password-toggle { position: absolute; top: 50%; right: .35rem; z-index: 3; display: grid; width: 34px; height: 34px; place-items: center; border: 0; border-radius: .45rem; background: transparent; color: #8297a4; font-size: .72rem; transform: translateY(-50%); cursor: pointer; }
    .password-toggle:hover, .password-toggle:focus { outline: 0; background: #edf4f8; color: #367bb8; }
    .profile-field-help { display: block; margin-top: .42rem; color: #8b9ba3; font-size: .65rem; }
    .profile-field-help i { margin-right: .25rem; color: #5d91ba; }
    .profile-form-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 0 1.4rem; border-top: 1px solid #e7eeed; }
    .profile-form-footer > span { color: #8b9aa2; font-size: .66rem; }
    .profile-form-footer > span i { margin-right: .25rem; color: #4e90bd; }
    .profile-save-button { padding: .68rem 1rem; border: 0; border-radius: .6rem; background: linear-gradient(135deg, #1769c2, #2d83d7); box-shadow: 0 7px 17px rgba(23,105,194,.2); color: #fff; font-size: .76rem; font-weight: 750; }
    .profile-save-button:hover, .profile-save-button:focus { color: #fff; box-shadow: 0 9px 21px rgba(23,105,194,.28); transform: translateY(-1px); }
    .profile-save-button i { margin-right: .35rem; }
    @media (max-width: 991.98px) {
        .profile-settings-layout { grid-template-columns: 1fr; }
        .profile-overview-card { max-width: none; }
    }
    @media (max-width: 575.98px) {
        .profile-settings-layout { padding-right: 0; padding-left: 0; }
        .profile-form-header, .profile-form-card form { padding-right: 1rem; padding-left: 1rem; }
        .profile-form-footer { align-items: flex-start; flex-direction: column; }
        .profile-save-button { width: 100%; }
    }
</style>
@stop

@section('js')
<script>
    var avatarInput = document.getElementById('profile-avatar');
    avatarInput.addEventListener('change', function () {
        var file = avatarInput.files && avatarInput.files[0];
        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        var preview = document.getElementById('profile-avatar-preview');
        var fallback = document.getElementById('profile-avatar-fallback');
        var reader = new FileReader();
        reader.addEventListener('load', function (event) {
            preview.src = event.target.result;
            preview.classList.remove('d-none');
            fallback.classList.add('d-none');
        });
        reader.readAsDataURL(file);
    });

    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.dataset.passwordToggle);
            var showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            button.innerHTML = '<i class="far ' + (showing ? 'fa-eye' : 'fa-eye-slash') + '"></i>';
            button.setAttribute('aria-label', (showing ? 'Show ' : 'Hide ') + input.id.replace(/-/g, ' '));
        });
    });
</script>
@stop
