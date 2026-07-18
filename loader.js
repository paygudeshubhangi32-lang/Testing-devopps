/**
 * Page Loader Controller
 * Hides loader after page fully loads
 */

(function() {
    'use strict';

    const loader = document.getElementById('pageLoader');
    if (!loader) return;

    function hideLoader() {
        loader.classList.add('loaded');
        // Remove from DOM after transition
        setTimeout(() => {
            loader.style.display = 'none';
        }, 600);
    }

    // Hide on window load
    window.addEventListener('load', function() {
        setTimeout(hideLoader, 300);
    });

    // Fallback: hide after 3 seconds max
    setTimeout(hideLoader, 3000);
})();
