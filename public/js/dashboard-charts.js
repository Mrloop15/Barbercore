(function () {
    const charts = Array.from(document.querySelectorAll('[data-chart-reveal]'));

    if (!charts.length) return;

    const root = document.documentElement;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let observer = null;

    function revealAll() {
        if (observer) observer.disconnect();
        charts.forEach((chart) => chart.classList.add('is-visible'));
        root.classList.remove('dashboard-motion-ready');
    }

    if (reducedMotion.matches || !('IntersectionObserver' in window)) {
        revealAll();
        return;
    }

    root.classList.add('dashboard-motion-ready');

    window.requestAnimationFrame(function () {
        window.requestAnimationFrame(function () {
            observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -6% 0px',
            });

            charts.forEach((chart) => observer.observe(chart));
        });
    });

    const handleMotionChange = function (event) {
        if (event.matches) revealAll();
    };

    if (typeof reducedMotion.addEventListener === 'function') {
        reducedMotion.addEventListener('change', handleMotionChange);
    } else if (typeof reducedMotion.addListener === 'function') {
        reducedMotion.addListener(handleMotionChange);
    }
}());
