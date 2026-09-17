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
