<?php
/**
 * Custom 404 Not Found Page
 */
if (file_exists(__DIR__ . '/config/app.php')) {
    require_once __DIR__ . '/config/app.php';
} else {
    define('BASE_URL', '/shubhangi');
    define('ASSETS_URL', '/shubhangi/assets');
}

$pageTitle = '404 Not Found';
$showNavbar = false;
$showFooter = false;

if (function_exists('sanitize')) {
    include __DIR__ . '/includes/header.php';
} else {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>404</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">';
    echo '<link href="' . ASSETS_URL . '/css/style.css" rel="stylesheet">';
    echo '</head><body>';
}
?>

<div class="error-page">
    <div class="container">
        <div class="error-code">404</div>
        <h2>Page Not Found</h2>
        <p>Oops! The page you're looking for doesn't exist or has been moved.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/shubhangi'; ?>/" class="btn btn-glow">
                <i class="bi bi-house me-2"></i>Go Home
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-arrow-left me-2"></i>Go Back
            </a>
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
