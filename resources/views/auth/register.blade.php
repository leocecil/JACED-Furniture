@extends('base.base')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FurniSpace — Create Account</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      height: 100vh;
      overflow: hidden;
      font-family: 'DM Sans', sans-serif;
      display: flex;
    }

    /* ── LEFT PANEL (inactive = login teaser) ── */
    .panel-left {
      width: 45%;
      height: 100vh;
      background: #1C1C1A;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 3rem;
      cursor: pointer;
      position: relative;
      transition: width 0.5s cubic-bezier(.77,0,.18,1);
      text-decoration: none;
      overflow: hidden;
    }

    .panel-left::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url('https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=60') center/cover no-repeat;
      opacity: 0.18;
      transition: opacity 0.4s;
    }

    .panel-left:hover::before { opacity: 0.28; }

    .panel-left-inner {
      position: relative;
      z-index: 1;
      text-align: center;
    }

    .panel-left-eyebrow {
      font-size: 11px;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: #A89880;
      margin-bottom: 1rem;
    }

    .panel-left-heading {
      font-family: 'Cormorant Garamond', serif;
      font-size: 42px;
      font-weight: 600;
      color: #F5F0E8;
      line-height: 1.1;
      margin-bottom: 1.25rem;
    }

    .panel-left-sub {
      font-size: 13px;
      color: #8C8070;
      line-height: 1.6;
      max-width: 260px;
      margin: 0 auto 2rem;
    }

    .panel-left-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 11px 24px;
      border: 0.5px solid #A89880;
      border-radius: 50px;
      color: #F5F0E8;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 500;
      letter-spacing: 0.04em;
      transition: background 0.2s, border-color 0.2s;
      text-decoration: none;
    }

    .panel-left-btn:hover {
    background: rgba(245,240,232,0.08);
    border-color: #C8B89A;
    }

    /* ── RIGHT PANEL (active = register) ── */
    .panel-right {
    width: 55%;
    height: 100vh;
    background: #FDFAF5;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 3rem 4rem;
    position: relative;
    overflow-y: auto;
    }

    /* ── FORM AREA ── */
    .form-wrap {
    width: 100%;
    max-width: 380px;
    }

    .fs-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 2.25rem;
    }

    .fs-logo-icon {
    width: 36px;
    height: 36px;
    background: #1C1C1A;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    .fs-logo-icon svg { width: 20px; height: 20px; }

    .fs-logo-text {
    font-weight: 500;
    font-size: 15px;
    letter-spacing: 0.06em;
    color: #1C1C1A;
    }

    .fs-eyebrow {
    font-size: 11px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #A89880;
    margin-bottom: 0.5rem;
    }

    .fs-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 36px;
    font-weight: 600;
    color: #1C1C1A;
    line-height: 1.15;
    margin-bottom: 1.75rem;
    }

    .fs-name-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    }

    .fs-field { margin-bottom: 1rem; }

    .fs-label {
    display: block;
    font-size: 11px;
    font-weight: 500;
    color: #6B6055;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 6px;
    }

    .fs-input {
    width: 100%;
    padding: 11px 14px;
    background: #F5F0E8;
    border: 0.5px solid #C8BFA8;
    border-radius: 9px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: #1C1C1A;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    }

    .fs-input::placeholder { color: #B5A898; }
    .fs-input:focus { border-color: #8C7A5E; background: #FFF; }
    .fs-input.error { border-color: #D08070; background: #FDF5F3; }

    .fs-field-error {
    font-size: 11.5px;
    color: #A04030;
    margin-top: 4px;
    display: none;
    }

    .fs-btn-primary {
    width: 100%;
    padding: 13px;
    background: #1C1C1A;
    color: #F5F0E8;
    border: none;
    border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.04em;
    cursor: pointer;
    margin-top: 1.25rem;
    transition: background 0.2s, transform 0.1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    }

    .fs-btn-primary:hover { background: #2E2E2C; }
    .fs-btn-primary:active { transform: scale(0.99); }

    .fs-agree {
    font-size: 12px;
    color: #A0937F;
    text-align: center;
    margin-top: 1.1rem;
    line-height: 1.6;
    }

    .fs-agree a { color: #6B5E48; text-decoration: underline; }

    /* ── DIVIDER LINE ── */
    .split-line {
    position: absolute;
    left: 45%;
    top: 0;
    width: 1px;
    height: 100%;
    background: #D6CDB8;
    z-index: 10;
    pointer-events: none;
    }

    /* ── VALIDATION ERRORS ── */
    .fs-alert {
    background: #FCF0EE;
    border: 0.5px solid #E8C4BC;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 12.5px;
    color: #8A3A2A;
    margin-bottom: 1.25rem;
    }

    @media (max-width: 768px) {
    body { flex-direction: column-reverse; overflow: auto; }
    .panel-left { width: 100%; height: 200px; }
    .panel-right { width: 100%; height: auto; padding: 3rem 2rem 3rem; }
    .panel-left-heading { font-size: 28px; }
    .split-line { display: none; }
    }
</style>
</head>
<body>

<div class="split-line"></div>

<!-- LEFT: LOGIN TEASER -->
<a class="panel-left" href="{{ route('login') }}">
    <div class="panel-left-inner">
    <p class="panel-left-eyebrow">Already have an account?</p>
    <h2 class="panel-left-heading">Welcome<br>Back.</h2>
    <p class="panel-left-sub">Sign in and pick up right where you left off with your saved designs.</p>
    <span class="panel-left-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Sign In
    </span>
    </div>
</a>

<!-- RIGHT: REGISTER FORM -->
<div class="panel-right">
    <div class="form-wrap">

    <div class="fs-logo">
        <div class="fs-logo-icon">
        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="3" width="6" height="6" rx="1" fill="#F5F0E8"/>
            <rect x="11" y="3" width="6" height="6" rx="1" fill="#F5F0E8" opacity="0.6"/>
            <rect x="3" y="11" width="6" height="6" rx="1" fill="#F5F0E8" opacity="0.6"/>
            <rect x="11" y="11" width="6" height="6" rx="1" fill="#F5F0E8" opacity="0.4"/>
        </svg>
        </div>
        <span class="fs-logo-text">FURNISPACE</span>
    </div>

    <p class="fs-eyebrow">Get started</p>
    <h1 class="fs-heading">Create your<br>account.</h1>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="fs-alert">
        {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="fs-name-row">
        <div class="fs-field">
            <label class="fs-label" for="first_name">First Name</label>
            <input class="fs-input @error('first_name') error @enderror"
            id="first_name" name="first_name" type="text"
            placeholder="Andi"
            value="{{ old('first_name') }}"
            autocomplete="given-name" required />
        </div>
        <div class="fs-field">
            <label class="fs-label" for="last_name">Last Name</label>
            <input class="fs-input @error('last_name') error @enderror"
            id="last_name" name="last_name" type="text"
            placeholder="Pratama"
            value="{{ old('last_name') }}"
            autocomplete="family-name" required />
        </div>
        </div>

        <div class="fs-field">
        <label class="fs-label" for="email">Email Address</label>
        <input class="fs-input @error('email') error @enderror"
            id="email" name="email" type="email"
            placeholder="you@example.com"
            value="{{ old('email') }}"
            autocomplete="email" required />
        </div>

        <div class="fs-field">
        <label class="fs-label" for="password">Password</label>
        <input class="fs-input @error('password') error @enderror"
            id="password" name="password" type="password"
            placeholder="Minimum 8 characters"
            autocomplete="new-password" required />
        </div>

        <div class="fs-field">
        <label class="fs-label" for="password_confirmation">Confirm Password</label>
        <input class="fs-input"
            id="password_confirmation" name="password_confirmation" type="password"
            placeholder="Repeat your password"
            autocomplete="new-password" required />
        </div>

        <button type="submit" class="fs-btn-primary">
        <span>Create Account</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>

    </form>

    <p class="fs-agree">
        By creating an account you agree to our
        <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
    </p>

    </div>
</div>

</body>
</html>
@endsection