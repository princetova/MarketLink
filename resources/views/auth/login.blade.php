@extends('layouts.auth')

@section('title', 'Sign In | MarketLink')

@section('content')
<main class="auth-shell" id="main-content">
    <section class="auth-visual" aria-labelledby="auth-editorial-title">
        <img src="{{ asset('images/marketplace/local-farmers.jpg') }}" alt="" aria-hidden="true">
        <div class="auth-visual__overlay" aria-hidden="true"></div>
        <div class="auth-visual__content">
            <a class="auth-visual__back" href="{{ route('marketplace.home') }}">
                <span aria-hidden="true">←</span> Back to marketplace
            </a>
            <div>
                <span class="auth-eyebrow">Rooted in community</span>
                <h1 id="auth-editorial-title">Fresh food starts<br>with real connections.</h1>
                <p>Discover local produce, trusted farmers, and a simpler way to shop fresh.</p>
            </div>
            <p class="auth-visual__note">Local harvests. Trusted pickup. Stronger communities.</p>
        </div>
    </section>

    <section class="auth-panel" aria-labelledby="login-title">
        <div class="auth-panel__inner">
            <a class="auth-brand" href="{{ route('marketplace.home') }}" aria-label="MarketLink marketplace homepage">
                <img src="{{ asset('images/brand/marketlink-logo.jpg') }}" alt="MarketLink eGreen Basket">
            </a>

            <div class="auth-heading">
                <span class="auth-eyebrow">Your local marketplace</span>
                <h2 id="login-title">Welcome back</h2>
                <p>Sign in to continue to MarketLink.</p>
            </div>

            <form class="auth-form" method="POST" action="{{ route('login.store') }}" data-login-form>
                @csrf

                <div class="auth-field">
                    <label for="email">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        inputmode="email"
                        required
                        autofocus
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                    >
                    @error('email')
                        <p class="auth-field__error" id="email-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field">
                    <div class="auth-label-row">
                        <label for="password">Password</label>
                        <button class="auth-future-link" type="button" data-auth-future="Password reset">Forgot password?</button>
                    </div>
                    <div class="auth-password">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                        >
                        <button
                            class="auth-password__toggle"
                            type="button"
                            data-password-toggle
                            aria-controls="password"
                            aria-label="Show password"
                        >Show</button>
                    </div>
                    @error('password')
                        <p class="auth-field__error" id="password-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-actions">
                    <label class="auth-remember" for="remember">
                        <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                        <span>Remember me</span>
                    </label>
                </div>

                <button class="auth-submit" type="submit" data-submit-button>
                    <span data-submit-label>Sign In</span>
                    <span class="auth-submit__spinner" aria-hidden="true"></span>
                </button>
            </form>

            <p class="auth-future-status" data-auth-future-status aria-live="polite"></p>

            <div class="auth-join" aria-labelledby="auth-join-title">
                <p id="auth-join-title">New to MarketLink?</p>
                <div>
                    <button type="button" data-auth-future="Customer registration">Join as a Customer</button>
                    <span aria-hidden="true">·</span>
                    <button type="button" data-auth-future="Farmer registration">Join as a Farmer</button>
                </div>
            </div>

            <p class="auth-legal">By signing in, you agree to use MarketLink for local, pickup-first commerce.</p>
        </div>
    </section>
</main>
@endsection
