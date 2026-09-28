<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f6f0">
    <meta name="description" content="@yield('meta_description', 'Discover fresh produce, nearby markets, and trusted local farmers with MarketLink.')">
    <title>@yield('title', 'MarketLink')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-site">
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <x-public.header />

    <main id="main-content">
        @yield('content')
    </main>

    <x-public.footer />

    <dialog class="notice-dialog" data-notice-dialog aria-labelledby="notice-title">
        <button class="notice-dialog__close" type="button" data-dialog-close aria-label="Close dialog">&times;</button>
        <span class="eyebrow">Coming soon</span>
        <h2 id="notice-title" data-dialog-title>Join MarketLink</h2>
        <p data-dialog-message>Accounts are being prepared. Explore the marketplace while we finish this experience.</p>
        <button class="button button--primary notice-dialog__action" type="button" data-dialog-close>Keep exploring</button>
    </dialog>
</body>
</html>
