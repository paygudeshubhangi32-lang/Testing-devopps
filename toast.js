/**
 * Toast Notification System
 * Programmatic toast creation with auto-dismiss
 */

function showToast(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toastContainer');
    const template = document.getElementById('toastTemplate');
    
    if (!container || !template) return;
    
    // Clone template
    const clone = template.content.cloneNode(true);
    const toast = clone.querySelector('.toast');
    const icon = clone.querySelector('.toast-icon');
    const msg = clone.querySelector('.toast-message');
    
    // Set message
    msg.textContent = message;
    
    // Set type-specific styles
    const typeConfig = {
        success: { bg: 'bg-success', icon: 'bi-check-circle-fill' },
        danger:  { bg: 'bg-danger',  icon: 'bi-x-circle-fill' },
        error:   { bg: 'bg-danger',  icon: 'bi-x-circle-fill' },
        warning: { bg: 'bg-warning', icon: 'bi-exclamation-triangle-fill' },
        info:    { bg: 'bg-info',    icon: 'bi-info-circle-fill' },
    };
    
    const config = typeConfig[type] || typeConfig.info;
    toast.classList.add(config.bg);
    icon.classList.add(config.icon);
    toast.setAttribute('data-bs-delay', duration);
    
    // Add to container
    container.appendChild(clone);
    
    // Get the last added toast element
    const addedToast = container.lastElementChild;
    
    // Initialize Bootstrap toast
    const bsToast = new bootstrap.Toast(addedToast);
    bsToast.show();
    
    // Remove from DOM after hidden
    addedToast.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Global shorthand functions
function toastSuccess(msg) { showToast(msg, 'success'); }
function toastError(msg)   { showToast(msg, 'danger'); }
function toastWarning(msg) { showToast(msg, 'warning'); }
function toastInfo(msg)    { showToast(msg, 'info'); }
