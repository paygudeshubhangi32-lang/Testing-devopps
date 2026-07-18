/**
 * Dark Mode Toggle
 * Uses Bootstrap 5.3 native data-bs-theme attribute
 * Persists preference in localStorage
 */

(function() {
    'use strict';

    const STORAGE_KEY = 'attendtrack-theme';
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Get saved theme or default to light
    function getSavedTheme() {
        return localStorage.getItem(STORAGE_KEY) || 'light';
    }

    // Apply theme
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateIcon(theme);
    }

    // Update toggle icon
    function updateIcon(theme) {
        if (!themeIcon) return;
        if (theme === 'dark') {
            themeIcon.className = 'bi bi-sun-fill';
        } else {
            themeIcon.className = 'bi bi-moon-fill';
        }
    }

    // Toggle theme
    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
    }

    // Initialize on page load
    applyTheme(getSavedTheme());

    // Bind click event
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
})();
