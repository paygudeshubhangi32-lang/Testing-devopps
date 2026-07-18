<?php
/**
 * Custom 500 Internal Server Error Page
 */
if (file_exists(__DIR__ . '/config/app.php')) {
    @require_once __DIR__ . '/config/app.php';
}

$pageTitle = '500 Server Error';
$showNavbar = false;
$showFooter = false;

if (function_exists('sanitize')) {
    include __DIR__ . '/includes/header.php';
} else {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>500 Server Error</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">';
    $assetsUrl = defined('ASSETS_URL') ? ASSETS_URL : '/shubhangi/assets';
    echo '<link href="' . $assetsUrl . '/css/style.css" rel="stylesheet">';
    echo '</head><body>';
}
?>

<div class="error-page">
    <div class="container">
        <div class="error-code">500</div>
        <h2>Internal Server Error</h2>
        <p>Something went wrong on our end. Please try again later or contact support if the issue persists.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/shubhangi'; ?>/" class="btn btn-glow">
                <i class="bi bi-house me-2"></i>Go Home
            </a>
            <button onclick="location.reload()" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-arrow-clockwise me-2"></i>Try Again
            </button>
        </div>
        <div class="mt-4">
            <small style="color:rgba(255,255,255,0.4);">
                <i class="bi bi-envelope me-1"></i>Contact: admin@attendtrack.com
            </small>
        </div>
    </div>
</div>

<?php
if (function_exists('sanitize')) {
    include __DIR__ . '/includes/footer.php';
} else {
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}
?>
