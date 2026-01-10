@extends('adminlte::auth.login')

@section('auth_body')

    <div class="text-center">
        <h1 class="login_title mb-4">Log In</h1>
    </div>
    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Email --}}
        <div class="form-group mb-3">
            <label class="small text-muted">Email address</label>
            <div class="input-group input-group-lg">
                <input type="email"
                       name="email"
                       class="form-control rounded-start"
                       placeholder="you@example.com"
                       required autofocus>
            </div>
        </div>        

        {{-- Password --}}
        <div class="form-group mb-4">
            <label class="small text-muted">Password</label>
            <div class="input-group input-group-lg">
                <input type="password"
                       name="password"
                       class="form-control rounded-start"
                       placeholder="••••••••"
                       required>

            </div>
        </div>

        @error('email')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm sign-in-btn">
            Log In
        </button>

        <div class="text-center extra_links">
            @if (Route::has('password.request'))

                Forgot password?
                <a href="{{ route('password.request') }}" class="text-sm text-primary">
                    Reset
                </a>
            @endif
        </div>
        <div class="text-center extra_links">
            @if (Route::has('register'))
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-sm text-primary">
                    Sign up
                </a>
            @endif
        </div>




    </form>
@endsection

@push('css')
<style>
/* Background */
.login-page {
    background: repeating-linear-gradient(135deg, rgb(15, 29, 64), rgb(15, 29, 64) calc(25%), rgb(20, 34, 75) calc(25%), rgb(20, 34, 75) calc(50%))
}

/* Card */




.login-card-body, .register-card-body {
    padding: 45px 60px 50px;
    border-radius: 8px;
}

.login-box .card {
    backdrop-filter: blur(16px);
    background: rgba(255, 255, 255, 0.9);
    border-radius: 1.25rem;
    box-shadow: 0 30px 60px rgba(0,0,0,.25);
    border: none;
}

.login-box, .register-box {
    width: auto;
}

.card-header, .card-footer {
    display: none;
}

/* Inputs */
.form-control {
    border: none;
    background: #f1f5f9;
}

.form-control:focus {
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,130,246,.25);
}

/* Button */
.btn-primary {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    border: none;
}

.btn-primary:hover {
    opacity: .95;
}

/* Typography */
.login-box h1 {
    font-size: 1.75rem;
}

.login-box label {
    font-weight: 500;
}
.login-logo a, .register-logo a {
    color: #fff;
}

.sign-in-btn {
    display: block;
    text-align: center;
    font-size: 18px;
    padding: 20px 38px;
    margin: 30px auto 0px;
    min-width: 264px;
    min-height: auto;
    border-radius: 100px;
    margin-bottom: 30px;
}
.extra_links {
    margin-top: 15px;
    font-size: 18px;
}
.extra_links a {
    font-size: 18px !important;
}
.login_title {
    font-weight: 700;
    color: #17191c;
    font-size: 2rem !important;
}

@media (min-width: 480px) {
    .card {
        min-width: 483px;
    }
}

@media (max-width: 480px) {
    .login-page {
        display: block !important;
    }
    .login-logo {
        display: none;
    }
    .login-box {
        margin-top: 0px;
        height: 100%;
    }
    .login-card-body, .register-card-body {
        border-radius: 0px;
        padding: 50px 30px;
    }
    .login-box .card {
        min-height: 100%;
    }
    
}

.text-danger {
    color: #f94747 !important;
}

</style>
@endpush
