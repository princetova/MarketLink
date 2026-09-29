@extends('layouts.auth')

@section('title', 'Create Customer Account | MarketLink')
@section('meta_description', 'Create your MarketLink customer account and start discovering fresh food from local farmers.')
@section('skip_label', 'Skip to registration')

@section('content')
<main class="auth-shell auth-shell--register" id="main-content">
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

    <section class="auth-panel auth-panel--register" aria-labelledby="register-title">
        <div class="auth-panel__inner">
            <a class="auth-brand" href="{{ route('marketplace.home') }}" aria-label="MarketLink marketplace homepage">
                <img src="{{ asset('images/brand/marketlink-logo.jpg') }}" alt="MarketLink eGreen Basket">
            </a>

            <div class="auth-heading">
                <span class="auth-eyebrow">Join the local marketplace</span>
                <h2 id="register-title">Create your customer account</h2>
                <p>Discover fresh local produce and reserve directly from farmers near you.</p>
            </div>

            <form class="auth-form" method="POST" action="{{ route('register.customer.store') }}" data-auth-form data-loading-label="Creating account…">
                @csrf

                <div class="auth-field">
                    <label for="name">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="120" required autofocus @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')<p class="auth-field__error" id="name-error" role="alert">{{ $message }}</p>@enderror
                </div>

                <div class="auth-field">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" maxlength="255" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')<p class="auth-field__error" id="email-error" role="alert">{{ $message }}</p>@enderror
                </div>

                <div class="auth-field">
                    <label for="phone">Phone Number</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" maxlength="32" required @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                    @error('phone')<p class="auth-field__error" id="phone-error" role="alert">{{ $message }}</p>@enderror
                </div>

                <div class="auth-field">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" autocomplete="street-address" maxlength="500" required @error('address') aria-invalid="true" aria-describedby="address-error" @enderror>{{ old('address') }}</textarea>
                    @error('address')<p class="auth-field__error" id="address-error" role="alert">{{ $message }}</p>@enderror
                </div>

                <div class="auth-field">
                    <label for="password">Password</label>
                    <div class="auth-password">
                        <input id="password" name="password" type="password" autocomplete="new-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        <button class="auth-password__toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password">Show</button>
                    </div>
                    @error('password')<p class="auth-field__error" id="password-error" role="alert">{{ $message }}</p>@enderror
                </div>

                <div class="auth-field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="auth-password">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                        <button class="auth-password__toggle" type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Show password confirmation">Show</button>
                    </div>
                </div>

                <button class="auth-submit" type="submit" data-submit-button>
                    <span data-submit-label>Create Account</span>
                    <span class="auth-submit__spinner" aria-hidden="true"></span>
                </button>
            </form>

            <div class="auth-join">
                <p>Already have an account?</p>
                <div><a href="{{ route('login') }}">Sign In</a></div>
            </div>
        </div>
    </section>
</main>
@endsection
