<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a class="brand" href="{{ route('marketplace.home') }}" aria-label="MarketLink homepage">
            <img src="{{ asset('images/brand/marketlink-logo.jpg') }}" alt="MarketLink eGreen Basket">
        </a>

        <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="primary-navigation">
            <span class="sr-only">Toggle navigation</span>
            <span></span><span></span><span></span>
        </button>

        <div class="site-header__panel" id="primary-navigation" data-nav-panel>
            <nav class="primary-nav" aria-label="Primary navigation">
                <a href="#fresh-produce">Browse Produce</a>
                <a href="#markets">Markets</a>
                <a href="#farmers">Farmers</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#about">About</a>
            </nav>

            <div class="site-header__actions">
                <a class="header-search" href="#quick-search" aria-label="Search MarketLink">
                    <svg aria-hidden="true" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                    <span>Search</span>
                </a>
                <a class="button button--ghost" href="{{ route('login') }}">Sign In</a>
                <a class="button button--primary" href="#join-marketlink">Join MarketLink</a>
            </div>
        </div>
    </div>
</header>
