<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f6f0">
    <meta name="description" content="@yield('meta_description', 'Sign in securely to your MarketLink account.')">
    <title>@yield('title', 'Sign In | MarketLink')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <a class="skip-link" href="#main-content">@yield('skip_label', 'Skip to sign in')</a>
    @yield('content')
</body>
</html>
