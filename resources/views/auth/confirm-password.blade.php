@extends('adminlte::auth.login')

@section('auth_body')
    <a href='{{ url('/') }}' class='auth-brand' aria-label='Galerive home'>
        <img class='auth-brand-logo' src='{{ asset('images/final_logo.png') }}' alt='Galerive'>
    </a>

    <div class='auth-heading'>
        <span class='auth-eyebrow'>Secure area</span>
        <h1>Confirm your password</h1>
        <p>This part of your studio contains sensitive information. Enter your password to continue.</p>
    </div>

    <form method='post' action='{{ route('password.confirm') }}'>
        @csrf
        <div class='auth-field'>
            <label for='password'>Password</label>
            <div class='auth-password-wrap'>
                <input id='password' type='password' name='password'
                       class='auth-input @error('password') is-invalid @enderror'
                       autocomplete='current-password' required autofocus>
                <button class='password-toggle' type='button' data-password-toggle='password' aria-label='Show password'>Show</button>
            </div>
            @error('password')
                <span class='auth-error' role='alert'>{{ $message }}</span>
            @enderror
        </div>

        <button type='submit' class='auth-submit'>Confirm password</button>
    </form>
@endsection

@push('css')
    @include('auth.partials.styles')
@endpush

@push('js')
    @include('auth.partials.password-toggle')
@endpush
