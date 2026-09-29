@extends('layouts.auth')

@section('title', 'Farmer Account Status | MarketLink')
@section('meta_description', 'Review the current approval status of your MarketLink farmer account.')
@section('skip_label', 'Skip to account status')

@section('content')
<main class="farmer-status-page" id="main-content">
    <section class="farmer-status-card farmer-status-card--{{ $tone }}" aria-labelledby="farmer-status-title">
        <a class="farmer-status-brand" href="{{ route('marketplace.home') }}" aria-label="MarketLink marketplace homepage">
            <img src="{{ asset('images/brand/marketlink-logo.jpg') }}" alt="MarketLink eGreen Basket">
        </a>

        @if (session('status'))
            <p class="farmer-status-flash" role="status">{{ session('status') }}</p>
        @endif

        <div class="farmer-status-symbol" aria-hidden="true"></div>
        <p class="farmer-status-badge">{{ $badge }}</p>
        <h1 id="farmer-status-title">{{ $heading }}</h1>
        <p class="farmer-status-message">{{ $message }}</p>

        @if ($farmerProfile)
            <p class="farmer-status-business">Account for <strong>{{ $farmerProfile->business_name }}</strong></p>
        @endif

        <aside class="farmer-status-panel" aria-labelledby="farmer-status-panel-title">
            <h2 id="farmer-status-panel-title">{{ $panel_heading }}</h2>
            <p>{{ $panel_message }}</p>
        </aside>

        <div class="farmer-status-actions">
            <a class="farmer-status-button farmer-status-button--primary" href="{{ route('marketplace.home') }}">{{ $action }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="farmer-status-button farmer-status-button--secondary" type="submit">Sign Out</button>
            </form>
        </div>
    </section>
</main>
@endsection
