/**
 * Shared MarketLink browser entry point.
 *
 * Keep this file framework-free. Feature-specific JavaScript should live near
 * its module and only be imported here when it is shared across the product.
 */
document.documentElement.classList.add('js');

const splash = document.querySelector('[data-marketlink-splash]');

if (splash instanceof HTMLElement) {
    const redirectUrl = splash.dataset.redirectUrl;
    const duration = Number.parseInt(splash.dataset.duration ?? '2200', 10);
    const fadeDuration = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 120 : 220;

    if (redirectUrl && Number.isFinite(duration)) {
        window.setTimeout(() => {
            splash.classList.add('is-leaving');
        }, Math.max(0, duration - fadeDuration));

        window.setTimeout(() => {
            window.location.replace(redirectUrl);
        }, duration);
    }
}
