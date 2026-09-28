<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F7F6F0">
    <title>MarketLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="marketlink-splash-page">
    <main
        class="marketlink-splash"
        data-marketlink-splash
        data-duration="2200"
        data-redirect-url="{{ route('marketplace.home') }}"
    >
        <section class="marketlink-splash__brand" aria-labelledby="marketlink-tagline">
            <img
                class="marketlink-splash__logo"
                src="{{ asset('images/brand/marketlink-logo.jpg') }}"
                width="1254"
                height="1254"
                alt="MarketLink — eGreen Basket"
                decoding="async"
                fetchpriority="high"
            >

            <p class="marketlink-splash__tagline" id="marketlink-tagline">Fresh from local farms.</p>
            <p class="marketlink-splash__subtitle">Connecting farmers and communities.</p>
        </section>

        <div class="marketlink-splash__loader" role="status">
            <span class="marketlink-splash__status">Loading MarketLink</span>
            <span class="marketlink-splash__loader-dot" aria-hidden="true"></span>
            <span class="marketlink-splash__loader-dot" aria-hidden="true"></span>
            <span class="marketlink-splash__loader-dot" aria-hidden="true"></span>
        </div>
    </main>
</body>
</html>
