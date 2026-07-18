<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer" style="z-index: 9999;">
</div>

<!-- Toast Template (hidden) -->
<template id="toastTemplate">
    <div class="toast align-items-center border-0 shadow-lg animate-slide-in" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="toast-icon bi fs-5"></i>
                <span class="toast-message"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</template>
