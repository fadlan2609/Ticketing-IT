import './bootstrap';

/**
 * Amanah Service Desk — Minimal JS
 * Hanya untuk micro-interactions
 */

// ---------- Auto dismiss toast ----------
document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('[data-toast]');
    toasts.forEach((toast, i) => {
        setTimeout(() => {
            toast.style.transition = 'opacity 300ms, transform 300ms';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            setTimeout(() => toast.remove(), 350);
        }, 5000 + i * 300);
    });
});

// ---------- Copy ticket number ----------
window.copyToClipboard = function (text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '✓ Copied';
        btn.classList.add('text-green-600');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('text-green-600');
        }, 1500);
    });
};

// ---------- Global search shortcut (Cmd/Ctrl + K) ----------
document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        const search = document.querySelector('[data-global-search]');
        if (search) search.focus();
    }
});