@extends('layouts.public')

@section('title', 'MarketLink | Fresh local food, closer to home')
@section('meta_description', 'Discover seasonal produce from nearby farmers, reserve what you need, and collect it fresh at a local market.')

@php
    $categories = [
        ['name' => 'Vegetables', 'icon' => 'leaf', 'tone' => 'green'],
        ['name' => 'Fruits', 'icon' => 'fruit', 'tone' => 'coral'],
        ['name' => 'Dairy', 'icon' => 'bottle', 'tone' => 'blue'],
        ['name' => 'Bakery', 'icon' => 'wheat', 'tone' => 'gold'],
        ['name' => 'Eggs', 'icon' => 'egg', 'tone' => 'sand'],
        ['name' => 'Honey', 'icon' => 'drop', 'tone' => 'amber'],
        ['name' => 'Plants', 'icon' => 'sprout', 'tone' => 'mint'],
    ];

    $products = [
        ['name' => 'Organic Tomatoes', 'farm' => 'Green Valley Farm', 'price' => '₦2,800', 'unit' => 'per basket', 'rating' => '4.9', 'badge' => 'Just picked', 'image' => 'images/marketplace/organic-tomatoes.jpg'],
        ['name' => 'Fresh Strawberries', 'farm' => 'Sunrise Acres', 'price' => '₦3,500', 'unit' => 'per punnet', 'rating' => '4.8', 'badge' => 'Seasonal', 'image' => 'images/marketplace/fresh-strawberries.jpg'],
        ['name' => 'Harvest Carrots', 'farm' => 'Riverbend Gardens', 'price' => '₦1,900', 'unit' => 'per bunch', 'rating' => '4.9', 'badge' => 'Farm fresh', 'image' => 'images/marketplace/carrots.jpg'],
        ['name' => 'Farm Fresh Eggs', 'farm' => 'Meadow Poultry', 'price' => '₦3,200', 'unit' => 'per dozen', 'rating' => '4.7', 'badge' => 'Free range', 'image' => 'images/marketplace/farm-fresh-eggs.jpg'],
    ];

    $markets = [
        ['name' => 'Central Farmers Market', 'place' => 'Kaduna Central', 'distance' => '2.4 km', 'schedule' => 'Saturday · 7:00 AM'],
        ['name' => 'Tudun Wada Market', 'place' => 'Tudun Wada', 'distance' => '3.1 km', 'schedule' => 'Daily · 8:00 AM'],
        ['name' => 'Barnawa Green Market', 'place' => 'Barnawa', 'distance' => '5.8 km', 'schedule' => 'Sunday · 8:30 AM'],
    ];

    $farmers = [
        ['name' => 'Amina Yusuf', 'initials' => 'AY', 'location' => 'Rigachikun · 6 km', 'specialty' => 'Leafy greens, herbs & tomatoes'],
        ['name' => 'David Clark', 'initials' => 'DC', 'location' => 'Green Valley · 9 km', 'specialty' => 'Root vegetables & seasonal fruit'],
        ['name' => 'Ngozi Farms', 'initials' => 'NF', 'location' => 'Barnawa · 5 km', 'specialty' => 'Free-range eggs & fresh dairy'],
    ];
@endphp

@section('content')
<div class="marketplace-home">
    <section class="marketplace-hero" aria-labelledby="hero-title">
        <video
            class="marketplace-hero__media"
            data-hero-video
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            poster="{{ asset('images/marketplace/hero-farmer.jpg') }}"
            aria-hidden="true"
            tabindex="-1"
        >
            <source src="{{ asset('videos/marketplace/marketlink-hero.mp4') }}" type="video/mp4">
        </video>
        <div class="marketplace-hero__overlay" aria-hidden="true"></div>
        <div class="container marketplace-hero__content">
            <span class="hero-kicker"><span aria-hidden="true">●</span> Grown nearby. Picked for you.</span>
            <h1 id="hero-title">Fresh from local farms.<br><em>Closer than you think.</em></h1>
            <p>Discover seasonal produce from farmers near you, reserve what you need, and collect it fresh at the market.</p>
            <div class="button-row">
                <a class="button button--primary button--large" href="#fresh-produce">Explore Fresh Produce</a>
                <a class="button button--light button--large" href="#markets">Find a Market</a>
            </div>
            <div class="hero-trust" aria-label="MarketLink benefits">
                <span>✓ Verified local farmers</span><span>✓ Fresh pickup</span><span>✓ No delivery guesswork</span>
            </div>
        </div>
    </section>

    <section class="marketplace-search" id="quick-search" aria-labelledby="search-title">
        <div class="container">
            <form class="quick-search" data-marketplace-search>
                <div class="quick-search__intro"><span class="eyebrow">Start local</span><h2 id="search-title">What are you looking for?</h2></div>
                <label class="search-field">
                    <span class="sr-only">Search produce, farmers, or markets</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                    <input type="search" name="query" placeholder="Search produce, farmers, or markets">
                </label>
                <label class="search-field search-field--location">
                    <span class="sr-only">Your location</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2"></circle></svg>
                    <input type="text" name="location" value="Kaduna, Nigeria" aria-label="Location">
                </label>
                <button class="button button--primary button--large" type="submit">Search</button>
                <p class="quick-search__status" data-search-status aria-live="polite"></p>
            </form>
        </div>
    </section>

    <section class="marketplace-categories section" aria-labelledby="categories-title">
        <div class="container">
            <x-public.section-heading eyebrow="Shop by category" title="Good food starts with what is in season" copy="Browse everyday staples and small-batch harvests from producers close to home." />
            <div class="category-grid" id="categories-title">
                @foreach ($categories as $category)
                    <a class="category-card category-card--{{ $category['tone'] }}" href="#fresh-produce">
                        <span class="category-card__icon" aria-hidden="true">
                            @switch($category['icon'])
                                @case('fruit') <svg viewBox="0 0 48 48"><path d="M25 14c7-5 12-3 14 1-4 2-9 2-14-1Z"></path><path d="M23 14c-1-5 1-8 4-11"></path><path d="M24 14C9 8 5 20 9 31c3 10 12 12 15 7 3 5 12 3 15-7 4-11 0-23-15-17Z"></path></svg> @break
                                @case('bottle') <svg viewBox="0 0 48 48"><path d="M19 5h10v8l5 7v21H14V20l5-7V5Z"></path><path d="M19 13h10M14 25h20"></path></svg> @break
                                @case('wheat') <svg viewBox="0 0 48 48"><path d="M24 43V12M24 18c-8-1-9-7-9-7 7-1 9 7 9 7Zm0 8c-9-1-11-8-11-8 8-1 11 8 11 8Zm0 8c-9-1-11-8-11-8 8-1 11 8 11 8Zm0-16c8-1 9-7 9-7-7-1-9 7-9 7Zm0 8c9-1 11-8 11-8-8-1-11 8-11 8Zm0 8c9-1 11-8 11-8-8-1-11 8-11 8Z"></path></svg> @break
                                @case('egg') <svg viewBox="0 0 48 48"><path d="M35 29c0 9-5 14-11 14s-11-5-11-14S19 5 24 5s11 15 11 24Z"></path></svg> @break
                                @case('drop') <svg viewBox="0 0 48 48"><path d="M24 5S11 21 11 30a13 13 0 0 0 26 0C37 21 24 5 24 5Z"></path><path d="M19 33c1 3 4 4 7 4"></path></svg> @break
                                @case('sprout') <svg viewBox="0 0 48 48"><path d="M24 42V20"></path><path d="M24 24C12 24 8 17 8 8c10 0 16 5 16 16Zm0 7c12 0 16-7 16-16-10 0-16 5-16 16Z"></path></svg> @break
                                @default <svg viewBox="0 0 48 48"><path d="M40 8C21 9 11 18 10 38c18 0 29-10 30-30Z"></path><path d="M10 38c8-10 15-16 26-24"></path></svg>
                            @endswitch
                        </span>
                        <span>{{ $category['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="marketplace-products section section--cream" id="fresh-produce" aria-labelledby="produce-title">
        <div class="container">
            <div class="section-heading-row">
                <x-public.section-heading eyebrow="Fresh this season" title="Picked recently. Ready for pickup." copy="Small harvests from local producers, shown here as a preview of the MarketLink marketplace." />
                <button class="text-link text-link--large" type="button" data-coming-soon="Produce catalogue">Browse all produce <span aria-hidden="true">→</span></button>
            </div>
            <div class="product-grid" id="produce-title">
                @foreach ($products as $product)<x-public.product-card :product="$product" />@endforeach
            </div>
        </div>
    </section>

    <section class="marketplace-markets section" id="markets" aria-labelledby="markets-title">
        <div class="container">
            <x-public.section-heading eyebrow="Near you" title="Local Markets Near You" copy="Plan a pickup around market days and collect directly from participating farmers." align="center" />
            <div class="markets-layout" id="markets-title">
                <div class="map-preview" role="img" aria-label="Illustrated map preview showing three markets near Kaduna">
                    <span class="map-road map-road--one"></span><span class="map-road map-road--two"></span><span class="map-river"></span>
                    <span class="map-label map-label--one">Kaduna Central</span><span class="map-label map-label--two">Barnawa</span>
                    <span class="map-marker map-marker--one"><b>1</b></span><span class="map-marker map-marker--two"><b>2</b></span><span class="map-marker map-marker--three"><b>3</b></span>
                    <div class="map-preview__note"><strong>3 pickup markets</strong><span>within 6 km of your location</span></div>
                </div>
                <div class="market-list">
                    @foreach ($markets as $index => $market)
                        <article class="market-card">
                            <span class="market-card__number">{{ $index + 1 }}</span>
                            <div><p>{{ $market['place'] }} · {{ $market['distance'] }}</p><h3>{{ $market['name'] }}</h3><span>{{ $market['schedule'] }}</span></div>
                            <button class="icon-button" type="button" data-coming-soon="Market directions" aria-label="View {{ $market['name'] }}">→</button>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="marketplace-farmers section section--forest" id="farmers" aria-labelledby="farmers-title">
        <div class="container farmers-layout">
            <div class="farmers-portrait">
                <img src="{{ asset('images/marketplace/local-farmers.jpg') }}" alt="Three local farmers at a produce market" loading="lazy">
                <div><strong>Local knowledge</strong><span>Real people behind every harvest</span></div>
            </div>
            <div>
                <x-public.section-heading eyebrow="Meet the growers" title="Meet Your Local Farmers" copy="Every farmer profile helps you discover what they grow, where they sell, and when you can collect." />
                <div class="farmer-list" id="farmers-title">@foreach ($farmers as $farmer)<x-public.farmer-card :farmer="$farmer" />@endforeach</div>
            </div>
        </div>
    </section>

    <section class="marketplace-steps section" id="how-it-works" aria-labelledby="steps-title">
        <div class="container">
            <x-public.section-heading eyebrow="Simple by design" title="From discovery to pickup in four steps" copy="MarketLink is pickup only—so you always know where and when to collect." align="center" />
            <ol class="steps-grid" id="steps-title">
                <li><span>01</span><div class="step-icon">⌕</div><h3>Discover</h3><p>Browse seasonal produce from local farmers and nearby markets.</p></li>
                <li><span>02</span><div class="step-icon">✓</div><h3>Reserve</h3><p>Pre-order what you want before market day.</p></li>
                <li><span>03</span><div class="step-icon">⌖</div><h3>Pick Up</h3><p>Collect your order at the selected market or farmer pickup point.</p></li>
                <li><span>04</span><div class="step-icon">♡</div><h3>Enjoy</h3><p>Enjoy fresh local food while supporting local producers.</p></li>
            </ol>
        </div>
    </section>

    <section class="community-feature section">
        <div class="container community-feature__card">
            <div class="community-feature__copy"><span class="eyebrow eyebrow--light">Community grown</span><h2>Support Local.<br>Grow a Healthier Community.</h2><p>Every reservation keeps more value close to home and makes fresh food easier to find.</p><ul><li>Better food</li><li>Stronger farmers</li><li>Greener future</li></ul></div>
            <img src="{{ asset('images/marketplace/local-farmers.jpg') }}" alt="Local farmers standing beside their fresh harvest" loading="lazy">
        </div>
    </section>

    <section class="marketplace-benefits section section--cream" id="why-marketlink" aria-labelledby="benefits-title">
        <div class="container benefits-layout">
            <x-public.section-heading eyebrow="Why MarketLink" title="Fresh choices, fewer unknowns" copy="A more direct way to shop from the people and places around you." />
            <ul class="benefit-list" id="benefits-title"><li><span>✓</span>Fresh local produce</li><li><span>✓</span>Support local farmers</li><li><span>✓</span>Know where your food comes from</li><li><span>✓</span>Reduce wasted trips</li><li><span>✓</span>Reserve before pickup</li></ul>
        </div>
    </section>

    <section class="marketplace-join section" id="join-marketlink" aria-labelledby="join-title">
        <div class="container">
            <x-public.section-heading eyebrow="One marketplace, two paths" title="Ready to grow with MarketLink?" copy="Come for the fresh food. Stay for the stronger local connections." align="center" />
            <div class="join-grid" id="join-title">
                <article class="join-card join-card--customer"><span>For customers</span><h3>Find food you can feel good about.</h3><p>Discover seasonal produce, reserve ahead, and collect at a convenient local pickup point.</p><a class="button button--primary button--large" href="{{ route('register.customer') }}">Join as a Customer</a></article>
                <article class="join-card join-card--farmer"><span>For farmers</span><h3>Reach nearby customers with less friction.</h3><p>Share your harvest, prepare reserved orders, and meet customers at your chosen pickup point.</p><a class="button button--light button--large" href="{{ route('register.farmer') }}">Join as a Farmer</a></article>
            </div>
        </div>
    </section>

    <section class="marketplace-about section" id="about" aria-labelledby="about-title">
        <div class="container about-card">
            <div class="about-card__mark" aria-hidden="true">M</div>
            <div><span class="eyebrow">About MarketLink</span><h2 id="about-title">A shorter path from local farms to local tables.</h2><p>MarketLink connects local farmers and customers through one simple marketplace where shoppers can discover produce, reserve items, find markets, and collect orders directly from local producers.</p></div>
            <a class="text-link text-link--large" href="#how-it-works">See how it works <span aria-hidden="true">→</span></a>
        </div>
    </section>
</div>
@endsection
