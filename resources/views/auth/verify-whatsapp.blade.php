@extends('adminlte::auth.login')

@section('auth_body')
    <a href="{{ url('/') }}" class="auth-brand" aria-label="Galerive home">
        <img class="auth-brand-logo" src="{{ asset('images/final_logo.png') }}" alt="Galerive">
    </a>

    <div class="auth-heading">
        <span class="auth-eyebrow">Verify your number</span>
        <h1>Check your WhatsApp</h1>
        <p>Enter the 6-digit code sent to <strong dir="ltr">{{ $pending->masked_phone }}</strong>. The code expires in 10 minutes.</p>
    </div>

    @if (session('status'))
        <div class="auth-alert auth-alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if (session('otp_debug_code'))
        <div class="auth-alert auth-alert-debug" role="status">
            Local testing code: <strong>{{ session('otp_debug_code') }}</strong>
        </div>
    @endif

    <form action="{{ route('registration.verify') }}" method="post" autocomplete="off">
        @csrf

        <div class="auth-field">
            <label for="otp">Verification code</label>
            <input id="otp"
                   type="text"
                   name="otp"
                   class="auth-input otp-input @error('otp') is-invalid @enderror"
                   value="{{ old('otp') }}"
                   placeholder="000000"
                   autocomplete="one-time-code"
                   inputmode="numeric"
                   pattern="[0-9]{6}"
                   maxlength="6"
                   aria-label="Six-digit WhatsApp verification code"
                   required
                   autofocus>
            @error('otp')
                <span class="auth-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="auth-submit">Verify and create account</button>
    </form>

    <form action="{{ route('registration.verify.resend') }}" method="post" class="resend-form">
        @csrf
        <button type="submit" class="resend-button">Send a new code</button>
    </form>

    <p class="auth-switch">
        Wrong number? <a href="{{ route('register') }}">Start again</a>
    </p>
@endsection

@push('css')
    @include('auth.partials.styles')
    <style>
        .otp-input { text-align: center; font-size: 1.45rem; font-weight: 800; letter-spacing: .45em; }
        .auth-alert-debug { background: #fff8db; color: #765b00; }
        .resend-form { margin-top: 16px; text-align: center; }
        .resend-button { border: 0; background: transparent; color: var(--auth-accent-dark); font-size: 13px; font-weight: 800; }
        .resend-button:hover { color: #5723ce; text-decoration: underline; }
    </style>
@endpush
