@props(['product'])

<article class="product-card">
    <div class="product-card__media">
        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }} from {{ $product['farm'] }}" loading="lazy">
        <span>{{ $product['badge'] }}</span>
    </div>
    <div class="product-card__body">
        <p class="product-card__farm">{{ $product['farm'] }}</p>
        <h3>{{ $product['name'] }}</h3>
        <div class="product-card__meta">
            <strong>{{ $product['price'] }}</strong>
            <span>{{ $product['unit'] }}</span>
        </div>
        <div class="product-card__footer">
            <span class="rating" aria-label="Rated {{ $product['rating'] }} out of 5">★ {{ $product['rating'] }}</span>
            <button type="button" class="text-link" data-coming-soon="Product details">View produce <span aria-hidden="true">→</span></button>
        </div>
    </div>
</article>
