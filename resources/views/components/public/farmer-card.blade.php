@props(['farmer'])

<article class="farmer-card">
    <div class="farmer-card__avatar" aria-hidden="true">{{ $farmer['initials'] }}</div>
    <div>
        <p class="farmer-card__location">{{ $farmer['location'] }}</p>
        <h3>{{ $farmer['name'] }}</h3>
        <p>{{ $farmer['specialty'] }}</p>
        <span class="verified-badge">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 12 2 2 4-5"></path><circle cx="12" cy="12" r="9"></circle></svg>
            Verified farmer
        </span>
    </div>
</article>
