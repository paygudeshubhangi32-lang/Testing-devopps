<?php
/**
 * Application Configuration
 * Attendance Management System
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

// Application Constants
define('APP_NAME', 'AttendTrack Pro');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Modern Attendance Management System');
define('APP_AUTHOR', 'Shubhangi');
define('APP_YEAR', date('Y'));

// Base URL - auto-detect
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . '://' . $host . '/shubhangi');
define('ASSETS_URL', BASE_URL . '/assets');

// File paths
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', __DIR__);
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . '/error.log');

// Include database configuration
require_once CONFIG_PATH . '/database.php';

// Include helper functions
require_once INCLUDES_PATH . '/functions.php';

// Auto-initialize database on first run
$pdo = getDBConnection();
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($tableCheck->rowCount() === 0) {
        require_once CONFIG_PATH . '/init.php';
        initializeDatabase($pdo);
    }
} catch (PDOException $e) {
    require_once CONFIG_PATH . '/init.php';
    initializeDatabase($pdo);
}
