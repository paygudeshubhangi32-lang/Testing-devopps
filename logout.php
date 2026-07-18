<?php
/**
 * Logout Handler
 * Securely destroys session and redirects to login
 */
require_once __DIR__ . '/config/app.php';

// Destroy all session data
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Start new session for flash message
session_start();
setFlashMessage('success', 'You have been logged out successfully.');
header('Location: ' . BASE_URL . '/login.php');
exit;
