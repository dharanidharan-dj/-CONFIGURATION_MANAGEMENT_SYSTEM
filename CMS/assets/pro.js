(() => {
    const root = document.documentElement;
    const key = 'cms_theme';
    const stored = localStorage.getItem(key);
    if (stored === 'dark' || stored === 'light') {
        root.setAttribute('data-theme', stored);
    }

    const toggle = document.getElementById('themeToggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            const current = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', current);
            localStorage.setItem(key, current);
        });
    }

    const toasts = document.querySelectorAll('.toast');
    toasts.forEach((toast, idx) => {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(8px)';
            setTimeout(() => toast.remove(), 250);
        }, 3000 + idx * 500);
    });
})();
