(() => {
    const root = document.documentElement;
    const toggle = document.getElementById('themeToggle');
    const themeColor = document.querySelector('meta[name="theme-color"]');
    const systemPreference = window.matchMedia('(prefers-color-scheme: dark)');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const storageKey = 'barbercore-theme';
    let animationTimer = null;

    if (!toggle) return;

    const savedTheme = () => {
        try {
            const theme = localStorage.getItem(storageKey);

            return theme === 'dark' || theme === 'light' ? theme : null;
        } catch (error) {
            return null;
        }
    };

    const updateControl = (theme) => {
        const isDark = theme === 'dark';
        const label = isDark ? 'Activar modo claro' : 'Activar modo oscuro';

        toggle.setAttribute('aria-label', label);
        toggle.setAttribute('aria-pressed', String(isDark));
        toggle.setAttribute('title', label);
        if (themeColor) themeColor.content = isDark ? '#171815' : '#C9A227';
    };

    const applyTheme = (theme, animate = false) => {
        if (animate && !reducedMotion.matches) {
            window.clearTimeout(animationTimer);
            root.classList.remove('theme-changing');
            void root.offsetWidth;
            root.classList.add('theme-changing');
            animationTimer = window.setTimeout(() => root.classList.remove('theme-changing'), 560);
        }

        root.dataset.theme = theme;
        updateControl(theme);
    };

    toggle.addEventListener('click', () => {
        const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';

        try {
            localStorage.setItem(storageKey, nextTheme);
        } catch (error) {
            // The selected theme still applies for the current page.
        }

        applyTheme(nextTheme, true);
    });

    const syncSystemTheme = (event) => {
        if (!savedTheme()) applyTheme(event.matches ? 'dark' : 'light', true);
    };

    if (typeof systemPreference.addEventListener === 'function') {
        systemPreference.addEventListener('change', syncSystemTheme);
    } else {
        systemPreference.addListener(syncSystemTheme);
    }

    updateControl(root.dataset.theme || 'light');
})();
