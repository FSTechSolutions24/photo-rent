<style>
    :root {
        --auth-accent: #8b5cf6;
        --auth-accent-dark: #6d3de7;
        --auth-ink: #17131f;
        --auth-muted: #686273;
        --auth-border: #ddd9e5;
        --auth-danger: #c72c41;
    }

    .login-page,
    .register-page {
        position: relative;
        min-height: 100vh;
        padding: 40px 20px;
        overflow-x: hidden;
        background:
            radial-gradient(circle at 15% 15%, rgba(139, 92, 246, .22), transparent 34%),
            radial-gradient(circle at 85% 85%, rgba(217, 70, 239, .13), transparent 30%),
            #050505;
        color: var(--auth-ink);
        font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif;
    }

    .login-page::before,
    .register-page::before {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        content: "";
        opacity: .14;
        background-image: linear-gradient(rgba(255,255,255,.12) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.12) 1px, transparent 1px);
        background-size: 64px 64px;
        -webkit-mask-image: linear-gradient(to bottom, black, transparent 80%);
        mask-image: linear-gradient(to bottom, black, transparent 80%);
    }

    .login-logo,
    .register-logo,
    .card-header,
    .card-footer {
        display: none;
    }

    .login-box,
    .register-box {
        z-index: 1;
        width: 100%;
        max-width: 470px;
        margin: auto;
    }

    .register-box { max-width: 500px; }

    .login-box .card,
    .register-box .card {
        margin: 0;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, .12) !important;
        border-radius: 24px;
        background: rgba(255, 255, 255, .97);
        box-shadow: 0 28px 80px rgba(0, 0, 0, .48);
        backdrop-filter: blur(20px);
    }

    .login-card-body,
    .register-card-body {
        padding: 38px 42px 40px;
        border-radius: 0;
        background: transparent;
    }

    .auth-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 34px;
        color: var(--auth-ink);
        font-size: 18px;
        font-weight: 800;
        letter-spacing: -.04em;
    }
    .auth-brand:hover { color: var(--auth-ink); text-decoration: none; }

    .auth-brand-mark {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--auth-accent), #d946ef);
        box-shadow: 0 8px 18px rgba(139, 92, 246, .28);
        color: #fff;
        font-style: italic;
        font-weight: 900;
        transform: rotate(5deg);
    }

    .auth-heading { margin-bottom: 30px; }
    .auth-eyebrow {
        display: block;
        margin-bottom: 7px;
        color: var(--auth-accent-dark);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
    }
    .auth-heading h1 {
        margin: 0 0 10px;
        color: var(--auth-ink);
        font-size: clamp(1.75rem, 5vw, 2.2rem);
        font-weight: 750;
        letter-spacing: -.04em;
        line-height: 1.12;
    }
    .auth-heading p {
        margin: 0;
        color: var(--auth-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .auth-field { margin-bottom: 19px; }
    .auth-field label,
    .auth-label-row label {
        display: block;
        margin: 0 0 8px;
        color: #312b39;
        font-size: 13px;
        font-weight: 700;
    }
    .auth-label-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 20px;
    }
    .auth-label-row a {
        color: var(--auth-accent-dark);
        font-size: 12px;
        font-weight: 700;
    }

    .auth-input {
        display: block;
        width: 100%;
        height: 52px;
        padding: 0 15px;
        border: 1px solid var(--auth-border);
        border-radius: 12px;
        outline: none;
        background: #fff;
        color: var(--auth-ink);
        font-size: 15px;
        line-height: 1;
        transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
    }
    .auth-input::placeholder { color: #aaa4b2; opacity: 1; }
    .auth-input:hover { border-color: #c3bdcb; }
    .auth-input:focus {
        border-color: var(--auth-accent);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, .13);
    }
    .auth-input.is-invalid {
        border-color: var(--auth-danger);
        background-image: none;
        padding-right: 15px;
    }
    .auth-input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(199, 44, 65, .1); }
    .auth-input:-webkit-autofill,
    .auth-input:-webkit-autofill:hover,
    .auth-input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--auth-ink);
        box-shadow: 0 0 0 1000px #fff inset, 0 0 0 4px rgba(139, 92, 246, .1);
        transition: background-color 9999s ease-out;
    }

    .auth-password-wrap { position: relative; }
    .auth-password-wrap .auth-input { padding-right: 64px; }
    .password-toggle {
        position: absolute;
        top: 50%;
        right: 12px;
        padding: 6px;
        border: 0;
        background: transparent;
        color: var(--auth-accent-dark);
        font-size: 11px;
        font-weight: 800;
        transform: translateY(-50%);
    }
    .password-toggle:focus-visible { outline: 2px solid var(--auth-accent); outline-offset: 2px; border-radius: 4px; }

    .auth-help,
    .auth-error {
        display: block;
        margin-top: 7px;
        font-size: 12px;
        line-height: 1.4;
    }
    .auth-help { color: var(--auth-muted); }
    .auth-error { color: var(--auth-danger); font-weight: 600; }

    .auth-check {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin: 0 0 23px;
        color: var(--auth-muted);
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
    }
    .auth-check input {
        width: 17px;
        height: 17px;
        margin: 0;
        accent-color: var(--auth-accent);
    }

    .auth-submit {
        width: 100%;
        min-height: 52px;
        padding: 13px 20px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--auth-accent), var(--auth-accent-dark));
        box-shadow: 0 12px 24px rgba(109, 61, 231, .24);
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .auth-submit:hover { transform: translateY(-1px); box-shadow: 0 15px 28px rgba(109, 61, 231, .3); }
    .auth-submit:active { transform: translateY(0); }
    .auth-submit:focus-visible { outline: 3px solid rgba(139, 92, 246, .3); outline-offset: 3px; }

    .auth-switch {
        margin: 25px 0 0;
        color: var(--auth-muted);
        font-size: 13px;
        text-align: center;
    }
    .auth-switch a { color: var(--auth-accent-dark); font-weight: 800; }
    .auth-label-row a:hover,
    .auth-switch a:hover { color: #5723ce; }

    .auth-alert {
        margin-bottom: 22px;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.5;
    }
    .auth-alert-success { background: #edf9f1; color: #176b38; }

    @media (max-width: 575.98px) {
        .login-page,
        .register-page { padding: 0; align-items: stretch; }
        .login-box,
        .register-box { max-width: none; min-height: 100vh; margin: 0; }
        .login-box .card,
        .register-box .card { min-height: 100vh; border: 0 !important; border-radius: 0; }
        .login-card-body,
        .register-card-body { padding: 30px 24px 36px; }
        .auth-brand { margin-bottom: 28px; }
    }
</style>
