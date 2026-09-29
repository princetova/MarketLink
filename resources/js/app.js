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

const navToggle = document.querySelector('[data-nav-toggle]');
const navPanel = document.querySelector('[data-nav-panel]');

if (navToggle instanceof HTMLButtonElement && navPanel instanceof HTMLElement) {
    const closeNavigation = () => {
        navPanel.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
    };

    navToggle.addEventListener('click', () => {
        const isOpen = navPanel.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });

    navPanel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeNavigation);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeNavigation();
        }
    });
}

const noticeDialog = document.querySelector('[data-notice-dialog]');

if (noticeDialog instanceof HTMLDialogElement) {
    const dialogTitle = noticeDialog.querySelector('[data-dialog-title]');
    const dialogMessage = noticeDialog.querySelector('[data-dialog-message]');

    document.querySelectorAll('[data-coming-soon]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const feature = trigger.getAttribute('data-coming-soon') ?? 'This feature';

            if (dialogTitle) {
                dialogTitle.textContent = `${feature} is coming soon`;
            }

            if (dialogMessage) {
                dialogMessage.textContent = 'This preview stays on the public homepage while account and marketplace functionality are built in their own focused stages.';
            }

            noticeDialog.showModal();
        });
    });

    noticeDialog.querySelectorAll('[data-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => noticeDialog.close());
    });

    noticeDialog.addEventListener('click', (event) => {
        if (event.target === noticeDialog) {
            noticeDialog.close();
        }
    });
}

const marketplaceSearch = document.querySelector('[data-marketplace-search]');

if (marketplaceSearch instanceof HTMLFormElement) {
    marketplaceSearch.addEventListener('submit', (event) => {
        event.preventDefault();

        const query = new FormData(marketplaceSearch).get('query')?.toString().trim();
        const status = marketplaceSearch.querySelector('[data-search-status]');

        if (status) {
            status.textContent = query
                ? `Search for “${query}” is ready for the marketplace catalogue stage.`
                : 'Enter a produce, farmer, or market name to preview your search.';
        }
    });
}

const heroVideo = document.querySelector('[data-hero-video]');

if (heroVideo instanceof HTMLVideoElement) {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    const syncHeroMotion = () => {
        if (reducedMotion.matches) {
            heroVideo.pause();
            heroVideo.currentTime = 0;
            return;
        }

        heroVideo.play().catch(() => {
            // The poster remains visible when a browser blocks autoplay.
        });
    };

    syncHeroMotion();
    reducedMotion.addEventListener('change', syncHeroMotion);
}

document.querySelectorAll('[data-password-toggle]').forEach((passwordToggle) => {
    const passwordInput = document.getElementById(passwordToggle.getAttribute('aria-controls') ?? '');

    if (passwordToggle instanceof HTMLButtonElement && passwordInput instanceof HTMLInputElement) {
        const passwordLabel = (passwordToggle.getAttribute('aria-label') ?? 'Show password')
            .replace(/^(Show|Hide)\s+/i, '');

        passwordToggle.addEventListener('click', () => {
            const willShowPassword = passwordInput.type === 'password';

            passwordInput.type = willShowPassword ? 'text' : 'password';
            passwordToggle.textContent = willShowPassword ? 'Hide' : 'Show';
            passwordToggle.setAttribute('aria-label', `${willShowPassword ? 'Hide' : 'Show'} ${passwordLabel}`);
        });
    }
});

const authForm = document.querySelector('[data-auth-form]');

if (authForm instanceof HTMLFormElement) {
    authForm.addEventListener('submit', () => {
        const submitButton = authForm.querySelector('[data-submit-button]');
        const submitLabel = authForm.querySelector('[data-submit-label]');

        if (submitButton instanceof HTMLButtonElement) {
            submitButton.disabled = true;
            submitButton.setAttribute('aria-busy', 'true');
        }

        if (submitLabel) {
            submitLabel.textContent = authForm.dataset.loadingLabel ?? 'Please wait…';
        }
    });
}

const authFutureStatus = document.querySelector('[data-auth-future-status]');

document.querySelectorAll('[data-auth-future]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
        if (authFutureStatus) {
            const feature = trigger.getAttribute('data-auth-future') ?? 'This feature';
            authFutureStatus.textContent = `${feature} is coming in its own MarketLink feature.`;
        }
    });
});
