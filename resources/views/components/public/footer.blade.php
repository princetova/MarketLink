<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand">
            <a class="brand brand--footer" href="{{ route('marketplace.home') }}">
                <img src="{{ asset('images/brand/marketlink-logo.jpg') }}" alt="MarketLink eGreen Basket">
            </a>
            <p>Fresh food. Local connections. Stronger communities.</p>
        </div>

        <div>
            <h2>Explore</h2>
            <a href="#fresh-produce">Fresh produce</a>
            <a href="#markets">Local markets</a>
            <a href="#farmers">Meet farmers</a>
        </div>

        <div>
            <h2>MarketLink</h2>
            <a href="#how-it-works">How it works</a>
            <a href="#about">About us</a>
            <a href="#why-marketlink">Why MarketLink</a>
        </div>

        <div>
            <h2>Account</h2>
            <a href="{{ route('login') }}">Sign in</a>
            <button type="button" data-coming-soon="Customer account">Join as a customer</button>
            <button type="button" data-coming-soon="Farmer account">Join as a farmer</button>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <p>&copy; {{ date('Y') }} MarketLink. All rights reserved.</p>
        <p>Pickup-first local commerce.</p>
    </div>
</footer>
