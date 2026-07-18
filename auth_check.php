<?php
/**
 * Authentication Check Middleware
 * Include this at the top of any protected page
 */

require_once __DIR__ . '/../config/app.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Please login to access this page.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Validate session integrity
$currentUser = getCurrentUser();
if (!$currentUser || !$currentUser['is_active']) {
    // User account deactivated or doesn't exist
    session_destroy();
    session_start();
    setFlashMessage('danger', 'Your session has expired. Please login again.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Session timeout (30 minutes)
$sessionTimeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $sessionTimeout) {
    session_unset();
    session_destroy();
    session_start();
    setFlashMessage('warning', 'Your session has timed out. Please login again.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically (every 5 minutes)
if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration']) > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// CSRF check for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken()) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            jsonResponse(['success' => false, 'message' => 'Invalid security token. Please refresh the page.'], 403);
        } else {
            setFlashMessage('danger', 'Invalid security token. Please try again.');
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL);
            exit;
        }
    }
}
