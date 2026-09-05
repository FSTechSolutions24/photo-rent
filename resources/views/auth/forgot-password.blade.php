@extends('adminlte::auth.passwords.email')

@section('auth_body')
    <a href="{{ url('/') }}" class="auth-brand" aria-label="VUE home">
        <span class="auth-brand-mark">V</span>
        <span>VUE</span>
    </a>

    <div class="auth-heading">
        <span class="auth-eyebrow">Account recovery</span>
        <h1>Reset your password</h1>
        <p>Enter the email address connected to your account and we’ll send you a secure password-reset link.</p>
    </div>

    @if (session('status'))
        <div class="auth-alert auth-alert-success" role="status">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="post" autocomplete="on">
        @csrf

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
                   required
                   autofocus>
            @error('email')
                <span id="email-error" class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="auth-submit">Send reset link</button>

        <p class="auth-switch">
            Remembered your password?
            <a href="{{ route('login') }}">Back to login</a>
        </p>
    </form>
@endsection

@push('css')
    @include('auth.partials.styles')
@endpush
