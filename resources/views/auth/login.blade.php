@extends('adminlte::auth.login')

@section('auth_body')
    <a href="{{ url('/') }}" class="auth-brand" aria-label="VUE home">
        <span class="auth-brand-mark">V</span>
        <span>VUE</span>
    </a>

    <div class="auth-heading">
        <span class="auth-eyebrow">Welcome back</span>
        <h1>Log in to your studio</h1>
        <p>Manage your clients, sessions, galleries, and deliveries.</p>
    </div>

    @if (session('status'))
        <div class="auth-alert auth-alert-success" role="status">{{ session('status') }}</div>
    @endif

    <form action="{{ route('login') }}" method="post" autocomplete="on">
        @csrf

        <div class="auth-field">
            <label for="email">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   class="auth-input @error('email') is-invalid @enderror"
                   placeholder="you@example.com"
                   value="{{ old('email') }}"
                   autocomplete="username"
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
            <div class="auth-label-row">
                <label for="password">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <div class="auth-password-wrap">
                <input id="password"
                       type="password"
                       name="password"
                       class="auth-input @error('password') is-invalid @enderror"
                       placeholder="Enter your password"
                       autocomplete="current-password"
                       @error('password') aria-describedby="password-error" aria-invalid="true" @enderror
                       required>
                <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password">Show</button>
            </div>
            @error('password')
                <span id="password-error" class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <label class="auth-check" for="remember">
            <input id="remember" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <span>Keep me signed in on this device</span>
        </label>

        <button type="submit" class="auth-submit">Log in</button>

        <p class="auth-switch">
            New to VUE?
            <a href="{{ route('register') }}">Create an account</a>
        </p>
    </form>
@endsection

@push('css')
    @include('auth.partials.styles')
@endpush

@push('js')
    @include('auth.partials.password-toggle')
@endpush
