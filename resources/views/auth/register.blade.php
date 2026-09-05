@extends('adminlte::auth.register')

@section('auth_body')
    <a href="{{ url('/') }}" class="auth-brand" aria-label="VUE home">
        <span class="auth-brand-mark">V</span>
        <span>VUE</span>
    </a>

    <div class="auth-heading">
        <span class="auth-eyebrow">Start with VUE</span>
        <h1>Create your studio account</h1>
        <p>Set up your workspace and begin delivering a better client experience.</p>
    </div>

    <form action="{{ route('register') }}" method="post" autocomplete="on">
        @csrf

        <div class="auth-field">
            <label for="name">Full name</label>
            <input id="name"
                   type="text"
                   name="name"
                   class="auth-input @error('name') is-invalid @enderror"
                   placeholder="Your full name"
                   value="{{ old('name') }}"
                   autocomplete="name"
                   @error('name') aria-describedby="name-error" aria-invalid="true" @enderror
                   required
                   autofocus>
            @error('name')
                <span id="name-error" class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="email">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   class="auth-input @error('email') is-invalid @enderror"
                   placeholder="you@example.com"
                   value="{{ old('email') }}"
                   autocomplete="email"
                   inputmode="email"
                   autocapitalize="none"
                   spellcheck="false"
                   @error('email') aria-describedby="email-error" aria-invalid="true" @enderror
                   required>
            @error('email')
                <span id="email-error" class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="auth-password-wrap">
                <input id="password"
                       type="password"
                       name="password"
                       class="auth-input @error('password') is-invalid @enderror"
                       placeholder="Create a password"
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
            <label for="password_confirmation">Confirm password</label>
            <div class="auth-password-wrap">
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       class="auth-input"
                       placeholder="Enter the password again"
                       autocomplete="new-password"
                       minlength="8"
                       required>
                <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show confirmed password">Show</button>
            </div>
        </div>

        <button type="submit" class="auth-submit">Create account</button>

        <p class="auth-switch">
            Already have an account?
            <a href="{{ route('login') }}">Log in</a>
        </p>
    </form>
@endsection

@push('css')
    @include('auth.partials.styles')
@endpush

@push('js')
    @include('auth.partials.password-toggle')
@endpush
