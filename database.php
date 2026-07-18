<?php
/**
 * Database Configuration & Connection
 * Attendance Management System
 * 
 * Uses PDO with prepared statements for security.
 */

// Database credentials
define('DB_HOST', '127.0.0.1'); // Using 127.0.0.1 instead of localhost avoids local socket lookup on Windows
define('DB_PORT', '3307');
define('DB_NAME', 'attendance_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO database connection (singleton pattern)
 * @return PDO
 */
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error and show user-friendly message
            error_log("Database Connection Error: " . $e->getMessage());
            
            // Check if database exists, if not try to create it
            try {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
                $tempPdo = new PDO($dsn, DB_USER, DB_PASS);
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $tempPdo = null;
                
                // Retry connection
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                
                // Run initialization
                require_once __DIR__ . '/init.php';
                initializeDatabase($pdo);
            } catch (PDOException $e2) {
                error_log("Database Creation Error: " . $e2->getMessage());
                die('<div style="font-family:Arial;padding:40px;text-align:center;">
                    <h2 style="color:#ef4444;">Database Connection Failed</h2>
                    <p>Please ensure MySQL is running in XAMPP Control Panel.</p>
                    <p style="color:#666;font-size:14px;">Error: ' . htmlspecialchars($e2->getMessage()) . '</p>
                </div>');
            }
        }
    }
    
    return $pdo;
}

/**
 * Execute a query with prepared statement
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @return PDOStatement
 */
function dbQuery($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch all results from a query
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array
 */
function dbFetchAll($sql, $params = []) {
    return dbQuery($sql, $params)->fetchAll();
}

/**
 * Fetch a single row
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array|false
 */
function dbFetchOne($sql, $params = []) {
    return dbQuery($sql, $params)->fetch();
}

/**
 * Get the last inserted ID
 * @return string
 */
function dbLastInsertId() {
    return getDBConnection()->lastInsertId();
}

/**
 * Get row count from last query
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return int
 */
function dbCount($sql, $params = []) {
    return dbQuery($sql, $params)->rowCount();
}
