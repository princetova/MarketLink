<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f6f0">
    <meta name="description" content="@yield('meta_description', 'MarketLink administration.')">
    <title>@yield('title', 'Admin | MarketLink')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page">
    <a class="skip-link" href="#main-content">Skip to main content</a>

    <header class="admin-header">
        <div class="admin-header__inner">
            <a class="admin-brand" href="{{ route('admin.farmers.approvals.index') }}" aria-label="MarketLink Farmer Approvals">
                <img src="{{ asset('images/brand/marketlink-logo.jpg') }}" alt="MarketLink eGreen Basket">
                <span>Admin</span>
            </a>

            <nav class="admin-header__actions" aria-label="Admin account actions">
                <a href="{{ route('marketplace.home') }}">Browse MarketLink</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Sign Out</button>
                </form>
            </nav>
        </div>
    </header>

    @yield('content')
</body>
</html>
