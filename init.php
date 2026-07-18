<?php
/**
 * Database Initialization Script
 * Auto-creates tables and seed data on first run
 */

function initializeDatabase($pdo) {
    try {
        // Read and execute the SQL file
        $sqlFile = dirname(__DIR__) . '/database.sql';
        
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Remove comments line by line to avoid filtering out actual queries
            $lines = explode("\n", $sql);
            $cleanLines = [];
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || strpos($trimmed, '--') === 0 || strpos($trimmed, '#') === 0) {
                    continue;
                }
                $cleanLines[] = $line;
            }
            $cleanSql = implode("\n", $cleanLines);
            
            // Split into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $cleanSql)),
                function($stmt) {
                    $stmt = trim($stmt);
                    return !empty($stmt) && 
                           stripos($stmt, 'CREATE DATABASE') === false &&
                           stripos($stmt, 'USE ') === false;
                }
            );
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        // Skip duplicate entry errors (seed data already exists)
                        if ($e->getCode() != '23000') {
                            error_log("Init SQL Error: " . $e->getMessage() . " | Statement: " . substr($statement, 0, 100));
                        }
                    }
                }
            }
            
            error_log("Database initialized successfully.");
            return true;
        } else {
            // Create tables manually if SQL file not found
            return createTablesManually($pdo);
        }
    } catch (Exception $e) {
        error_log("Database initialization failed: " . $e->getMessage());
        return false;
    }
}

function createTablesManually($pdo) {
    $queries = [
        // Users table
        "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `role` ENUM('admin', 'teacher') NOT NULL DEFAULT 'teacher',
            `phone` VARCHAR(20) DEFAULT NULL,
            `avatar` VARCHAR(255) DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `last_login` DATETIME DEFAULT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        
        // Classes table
        "CREATE TABLE IF NOT EXISTS `classes` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `class_name` VARCHAR(100) NOT NULL,
            `section` VARCHAR(10) DEFAULT NULL,
            `description` TEXT DEFAULT NULL,
            `teacher_id` INT DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        
        // Subjects table
        "CREATE TABLE IF NOT EXISTS `subjects` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `subject_name` VARCHAR(100) NOT NULL,
            `subject_code` VARCHAR(20) NOT NULL UNIQUE,
            `description` TEXT DEFAULT NULL,
            `class_id` INT DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        
        // Students table
        "CREATE TABLE IF NOT EXISTS `students` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `student_id` VARCHAR(20) NOT NULL UNIQUE,
            `first_name` VARCHAR(50) NOT NULL,
            `last_name` VARCHAR(50) NOT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `phone` VARCHAR(20) DEFAULT NULL,
            `gender` ENUM('male', 'female', 'other') DEFAULT NULL,
            `date_of_birth` DATE DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `class_id` INT DEFAULT NULL,
            `guardian_name` VARCHAR(100) DEFAULT NULL,
            `guardian_phone` VARCHAR(20) DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        
        // Attendance table
        "CREATE TABLE IF NOT EXISTS `attendance` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `student_id` INT NOT NULL,
            `class_id` INT NOT NULL,
            `subject_id` INT DEFAULT NULL,
            `date` DATE NOT NULL,
            `status` ENUM('present', 'absent', 'late', 'excused') NOT NULL DEFAULT 'present',
            `remarks` TEXT DEFAULT NULL,
            `marked_by` INT DEFAULT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY `uk_attendance_record` (`student_id`, `class_id`, `subject_id`, `date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        
        // Settings table
        "CREATE TABLE IF NOT EXISTS `settings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `setting_key` VARCHAR(50) NOT NULL UNIQUE,
            `setting_value` TEXT DEFAULT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        
        // Default admin user (password: admin123)
        "INSERT IGNORE INTO `users` (`username`, `email`, `password`, `full_name`, `role`) VALUES
         ('admin', 'admin@attendance.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin')",
        
        // Default settings
        "INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES
         ('site_name', 'AttendTrack Pro'),
         ('site_description', 'Modern Attendance Management System')"
    ];
    
    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
        } catch (PDOException $e) {
            error_log("Manual table creation error: " . $e->getMessage());
        }
    }
    
    return true;
}
