@extends('adminlte::auth.passwords.reset')

@section('auth_body')
    <a href='{{ url('/') }}' class='auth-brand' aria-label='Galerive home'>
        <img class='auth-brand-logo' src='{{ asset('images/final_logo.png') }}' alt='Galerive'>
    </a>

    <div class='auth-heading'>
        <span class='auth-eyebrow'>Secure your account</span>
        <h1>Choose a new password</h1>
        <p>Create a strong password you have not used for this account before.</p>
    </div>

    <form method='post' action='{{ route('password.update') }}' autocomplete='on'>
        @csrf
        <input type='hidden' name='token' value='{{ $request->route('token') }}'>

        <div class='auth-field'>
            <label for='email'>Email address</label>
            <input id='email' type='email' name='email'
                   class='auth-input @error('email') is-invalid @enderror'
                   value='{{ old('email', $request->email) }}'
                   autocomplete='email' required autofocus>
            @error('email')
                <span class='auth-error' role='alert'>{{ $message }}</span>
            @enderror
        </div>

        <div class='auth-field'>
            <label for='password'>New password</label>
            <div class='auth-password-wrap'>
                <input id='password' type='password' name='password'
                       class='auth-input @error('password') is-invalid @enderror'
                       autocomplete='new-password' required>
                <button class='password-toggle' type='button' data-password-toggle='password' aria-label='Show password'>Show</button>
            </div>
            <span class='auth-help'>Use at least 8 characters.</span>
            @error('password')
                <span class='auth-error' role='alert'>{{ $message }}</span>
            @enderror
        </div>

        <div class='auth-field'>
            <label for='password_confirmation'>Confirm new password</label>
            <div class='auth-password-wrap'>
                <input id='password_confirmation' type='password' name='password_confirmation'
                       class='auth-input' autocomplete='new-password' required>
                <button class='password-toggle' type='button' data-password-toggle='password_confirmation' aria-label='Show password confirmation'>Show</button>
            </div>
        </div>

        <button type='submit' class='auth-submit'>Update password</button>
        <p class='auth-switch'><a href='{{ route('login') }}'>Back to login</a></p>
    </form>
@endsection

@push('css')
    @include('auth.partials.styles')
@endpush

@push('js')
    @include('auth.partials.password-toggle')
@endpush
