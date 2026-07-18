-- =====================================================
-- Attendance Management System - Database Schema
-- Version: 1.0.0
-- Created: 2026-07-16
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `attendance_db` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `attendance_db`;

-- =====================================================
-- Table: users
-- Stores admin and teacher accounts
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
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
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: classes
-- Stores class/section definitions
-- =====================================================
CREATE TABLE IF NOT EXISTS `classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_name` VARCHAR(100) NOT NULL,
  `section` VARCHAR(10) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `teacher_id` INT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_class_section` (`class_name`, `section`),
  INDEX `idx_classes_active` (`is_active`),
  CONSTRAINT `fk_classes_teacher` FOREIGN KEY (`teacher_id`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: subjects
-- Stores subject catalog
-- =====================================================
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_name` VARCHAR(100) NOT NULL,
  `subject_code` VARCHAR(20) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `class_id` INT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_subjects_active` (`is_active`),
  CONSTRAINT `fk_subjects_class` FOREIGN KEY (`class_id`) 
    REFERENCES `classes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: students
-- Stores student profiles
-- =====================================================
CREATE TABLE IF NOT EXISTS `students` (
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
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_students_class` (`class_id`),
  INDEX `idx_students_active` (`is_active`),
  INDEX `idx_students_name` (`first_name`, `last_name`),
  CONSTRAINT `fk_students_class` FOREIGN KEY (`class_id`) 
    REFERENCES `classes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: attendance
-- Stores daily attendance records
-- =====================================================
CREATE TABLE IF NOT EXISTS `attendance` (
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
  UNIQUE KEY `uk_attendance_record` (`student_id`, `class_id`, `subject_id`, `date`),
  INDEX `idx_attendance_date` (`date`),
  INDEX `idx_attendance_status` (`status`),
  INDEX `idx_attendance_class_date` (`class_id`, `date`),
  CONSTRAINT `fk_attendance_student` FOREIGN KEY (`student_id`) 
    REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attendance_class` FOREIGN KEY (`class_id`) 
    REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attendance_subject` FOREIGN KEY (`subject_id`) 
    REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_attendance_user` FOREIGN KEY (`marked_by`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: settings
-- Stores application settings
-- =====================================================
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(50) NOT NULL UNIQUE,
  `setting_value` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Seed Data: Default Admin User
-- Password: admin123 (bcrypt hashed)
-- =====================================================
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `role`, `phone`) VALUES
('admin', 'admin@attendance.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', '+91-9876543210'),
('teacher', 'teacher@attendance.com', '$2y$10$Wj1Ol4BQ.SFGEDqnyDFRWOyXiwmNqO5cdTVrCjLuExYHsShq4GMPK', 'Demo Teacher', 'teacher', '+91-9876543211');

-- =====================================================
-- Seed Data: Sample Classes
-- =====================================================
INSERT INTO `classes` (`class_name`, `section`, `description`, `teacher_id`) VALUES
('Class 10', 'A', 'Science stream - Section A', 2),
('Class 10', 'B', 'Commerce stream - Section B', 2),
('Class 11', 'A', 'Science stream - Section A', 2),
('Class 11', 'B', 'Arts stream - Section B', 2),
('Class 12', 'A', 'Science stream - Section A', 2);

-- =====================================================
-- Seed Data: Sample Subjects
-- =====================================================
INSERT INTO `subjects` (`subject_name`, `subject_code`, `description`, `class_id`) VALUES
('Mathematics', 'MATH101', 'Advanced Mathematics', 1),
('Physics', 'PHY101', 'Physics Fundamentals', 1),
('Chemistry', 'CHEM101', 'Organic & Inorganic Chemistry', 1),
('English', 'ENG101', 'English Literature & Grammar', 1),
('Computer Science', 'CS101', 'Programming & Data Structures', 1),
('Biology', 'BIO101', 'Cell Biology & Genetics', 3),
('History', 'HIS101', 'Modern World History', 4),
('Economics', 'ECO101', 'Microeconomics & Macroeconomics', 2);

-- =====================================================
-- Seed Data: Sample Students
-- =====================================================
INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `phone`, `gender`, `date_of_birth`, `class_id`, `guardian_name`, `guardian_phone`) VALUES
('STU001', 'Aarav', 'Sharma', 'aarav.sharma@student.com', '9876500001', 'male', '2009-03-15', 1, 'Rajesh Sharma', '9876500101'),
('STU002', 'Priya', 'Patel', 'priya.patel@student.com', '9876500002', 'female', '2009-07-22', 1, 'Suresh Patel', '9876500102'),
('STU003', 'Arjun', 'Singh', 'arjun.singh@student.com', '9876500003', 'male', '2009-01-10', 1, 'Vikram Singh', '9876500103'),
('STU004', 'Ananya', 'Gupta', 'ananya.gupta@student.com', '9876500004', 'female', '2009-11-05', 1, 'Amit Gupta', '9876500104'),
('STU005', 'Rohan', 'Kumar', 'rohan.kumar@student.com', '9876500005', 'male', '2009-05-18', 1, 'Sanjay Kumar', '9876500105'),
('STU006', 'Sneha', 'Verma', 'sneha.verma@student.com', '9876500006', 'female', '2009-09-30', 2, 'Deepak Verma', '9876500106'),
('STU007', 'Karan', 'Mehta', 'karan.mehta@student.com', '9876500007', 'male', '2008-02-14', 2, 'Prakash Mehta', '9876500107'),
('STU008', 'Divya', 'Joshi', 'divya.joshi@student.com', '9876500008', 'female', '2008-06-25', 3, 'Ramesh Joshi', '9876500108'),
('STU009', 'Aditya', 'Reddy', 'aditya.reddy@student.com', '9876500009', 'male', '2008-12-08', 3, 'Krishna Reddy', '9876500109'),
('STU010', 'Ishita', 'Nair', 'ishita.nair@student.com', '9876500010', 'female', '2007-04-20', 5, 'Suresh Nair', '9876500110'),
('STU011', 'Varun', 'Desai', 'varun.desai@student.com', '9876500011', 'male', '2009-08-12', 1, 'Nikhil Desai', '9876500111'),
('STU012', 'Meera', 'Iyer', 'meera.iyer@student.com', '9876500012', 'female', '2009-10-03', 1, 'Ganesh Iyer', '9876500112'),
('STU013', 'Rahul', 'Chopra', 'rahul.chopra@student.com', '9876500013', 'male', '2008-01-27', 2, 'Anil Chopra', '9876500113'),
('STU014', 'Pooja', 'Agarwal', 'pooja.agarwal@student.com', '9876500014', 'female', '2008-07-16', 4, 'Vinod Agarwal', '9876500114'),
('STU015', 'Siddharth', 'Rao', 'siddharth.rao@student.com', '9876500015', 'male', '2007-11-09', 5, 'Mohan Rao', '9876500115');

-- =====================================================
-- Seed Data: Sample Attendance (Today and past week)
-- =====================================================
INSERT INTO `attendance` (`student_id`, `class_id`, `subject_id`, `date`, `status`, `marked_by`) VALUES
(1, 1, 1, CURDATE(), 'present', 2),
(2, 1, 1, CURDATE(), 'present', 2),
(3, 1, 1, CURDATE(), 'absent', 2),
(4, 1, 1, CURDATE(), 'present', 2),
(5, 1, 1, CURDATE(), 'late', 2),
(6, 2, 8, CURDATE(), 'present', 2),
(7, 2, 8, CURDATE(), 'present', 2),
(11, 1, 1, CURDATE(), 'present', 2),
(12, 1, 1, CURDATE(), 'present', 2),
(1, 1, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 2),
(2, 1, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 2),
(3, 1, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 2),
(4, 1, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'absent', 2),
(5, 1, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 2),
(1, 1, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 2),
(2, 1, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'late', 2),
(3, 1, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 2),
(4, 1, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 2),
(5, 1, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 2);

-- =====================================================
-- Seed Data: Default Settings
-- =====================================================
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'AttendTrack Pro'),
('site_description', 'Modern Attendance Management System'),
('admin_email', 'admin@attendance.com'),
('timezone', 'Asia/Kolkata'),
('attendance_start_time', '08:00'),
('attendance_end_time', '17:00'),
('late_threshold_minutes', '15'),
('min_attendance_percentage', '75');
