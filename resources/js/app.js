document.addEventListener('alpine:init', () => {
    // Scroll-reveal: add .reveal-visible class when element enters viewport
    Alpine.directive('reveal', (el) => {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        el.style.animationPlayState = 'running';
                        observer.unobserve(el);
                    }
                });
            },
            { threshold: 0.1 }
        );

        // Pause animation until visible
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
});

// Disable submit buttons on classic (non-Livewire) form submissions so the
// button shows its loading state and users can't double-submit. Livewire forms
// manage their own loading state via wire:loading.
document.addEventListener(
    'submit',
    (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || form.hasAttribute('wire:submit')) {
            return;
        }

        form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => {
            button.setAttribute('aria-busy', 'true');
            button.disabled = true;
        });
    },
    true
);

// Restore buttons when the page is restored from the browser's back/forward cache.
window.addEventListener('pageshow', () => {
    document.querySelectorAll('[aria-busy="true"]').forEach((element) => {
        element.removeAttribute('aria-busy');
        element.disabled = false;
    });
});

