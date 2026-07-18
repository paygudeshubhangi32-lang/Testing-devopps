/**
 * Main Application JavaScript
 * Global utilities, AJAX helpers, and event listeners
 */

(function() {
    'use strict';

    // =====================================================
    // Scroll Animations (Intersection Observer)
    // =====================================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', function() {
        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in-up, .animate-fade-in, .animate-scale-in, .animate-slide-left, .animate-slide-right').forEach(function(el) {
            observer.observe(el);
        });
        
        // Add page-enter animation to main content
        const mainContent = document.querySelector('.admin-content, main');
        if (mainContent) {
            mainContent.classList.add('page-enter');
        }
    });

    // =====================================================
    // Navbar Scroll Effect
    // =====================================================
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('mainNavbar');
        if (navbar) {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
    });

    // =====================================================
    // Admin Sidebar Toggle (Mobile)
    // =====================================================
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');

        function openSidebar() {
            if (sidebar) sidebar.classList.add('show');
            if (overlay) overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    });

    // =====================================================
    // Counter Animation
    // =====================================================
    window.animateCounters = function() {
        document.querySelectorAll('[data-count]').forEach(function(el) {
            const target = parseInt(el.getAttribute('data-count'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            function update() {
                current += step;
                if (current < target) {
                    el.textContent = Math.floor(current);
                    requestAnimationFrame(update);
                } else {
                    el.textContent = target;
                }
            }

            update();
        });
    };

    // Auto-run counters when they become visible
    const counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounters();
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-count]').forEach(function(el) {
            counterObserver.observe(el);
        });
    });

    // =====================================================
    // AJAX Helpers
    // =====================================================
    window.ajaxRequest = function(url, method, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        
        if (method === 'POST' && !(data instanceof FormData)) {
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            try {
                const response = JSON.parse(xhr.responseText);
                callback(null, response);
            } catch(e) {
                callback(new Error('Invalid response'), null);
            }
        };
        
        xhr.onerror = function() {
            callback(new Error('Network error'), null);
        };
        
        if (data instanceof FormData) {
            xhr.send(data);
        } else if (typeof data === 'object') {
            const params = new URLSearchParams(data).toString();
            xhr.send(params);
        } else {
            xhr.send(data);
        }
    };

    // =====================================================
    // Confirm Delete Dialog
    // =====================================================
    window.confirmDelete = function(message, callback) {
        const modal = document.getElementById('confirmDeleteModal');
        if (modal) {
            const bsModal = new bootstrap.Modal(modal);
            const msgEl = modal.querySelector('.confirm-message');
            const confirmBtn = modal.querySelector('.confirm-delete-btn');
            
            if (msgEl) msgEl.textContent = message || 'Are you sure you want to delete this item?';
            
            // Remove old listeners
            const newBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
            
            newBtn.addEventListener('click', function() {
                bsModal.hide();
                if (callback) callback();
            });
            
            bsModal.show();
        } else {
            if (confirm(message || 'Are you sure you want to delete this item?')) {
                if (callback) callback();
            }
        }
    };

    // =====================================================
    // Tooltip Initialization
    // =====================================================
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(el) {
            return new bootstrap.Tooltip(el);
        });
    });

    // =====================================================
    // Smooth scroll for anchor links
    // =====================================================
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href^="#"]');
        if (link) {
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });

})();
