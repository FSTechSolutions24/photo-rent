@extends('adminlte::auth.passwords.reset')

@section('auth_body')
    <a href="{{ url('/') }}" class="auth-brand" aria-label="VUE home">
        <span class="auth-brand-mark">V</span>
        <span>VUE</span>
    </a>

    <div class="auth-heading">
        <span class="auth-eyebrow">Secure your account</span>
        <h1>Choose a new password</h1>
        <p>Create a new password for your VUE studio account.</p>
    </div>

    <form action="{{ url('/password/reset') }}" method="post" autocomplete="on">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="auth-field">
            <label for="email">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   class="auth-input @error('email') is-invalid @enderror"
                   placeholder="you@example.com"
                   value="{{ old('email', request('email')) }}"
                   autocomplete="email"
                   inputmode="email"
                   autocapitalize="none"
                   spellcheck="false"
                   @error('email') aria-describedby="email-error" aria-invalid="true" @enderror
                   required
                   autofocus>
            @error('email')
                <span id="email-error" class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">New password</label>
            <div class="auth-password-wrap">
                <input id="password"
                       type="password"
                       name="password"
                       class="auth-input @error('password') is-invalid @enderror"
                       placeholder="Create a new password"
                       autocomplete="new-password"
                       aria-describedby="password-help @error('password') password-error @enderror"
                       @error('password') aria-invalid="true" @enderror
                       minlength="8"
                       required>
                <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password">Show</button>
            </div>
            <span id="password-help" class="auth-help">Use at least 8 characters.</span>
            @error('password')
                <span id="password-error" class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password_confirmation">Confirm new password</label>
            <div class="auth-password-wrap">
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       class="auth-input"
                       placeholder="Enter the new password again"
                       autocomplete="new-password"
                       minlength="8"
                       required>
                <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show confirmed password">Show</button>
            </div>
        </div>

        <button type="submit" class="auth-submit">Update password</button>

        <p class="auth-switch"><a href="{{ route('login') }}">Back to login</a></p>
    </form>
@endsection

@push('css')
    @include('auth.partials.styles')
@endpush

@push('js')
    @include('auth.partials.password-toggle')
@endpush
