# 📚 Complete System Documentation — AttendTrack Pro

Welcome to the official developer documentation for **AttendTrack Pro**. This document details the system design pattern, database relational structure, custom API routes, security safeguards, and implementation metrics.

---

## 🏛️ System Architecture

AttendTrack Pro uses a lightweight, clean, file-based modular MVC architecture in vanilla PHP (without heavy external frameworks) to guarantee high performance, security, and low loading times.

### Component Map

*   **View Layer (HTML5/CSS3/Bootstrap 5.3):** Serves dynamic layout shells. Automatically supports dark mode attributes using JS and responsive media styles.
*   **Controller Layer (API endpoints in `api/`):** Handles asynchronous requests (AJAX) and responds with formatted JSON outputs.
*   **Model Layer (DB Helpers in `config/database.php`):** Implements single PDO database instance wrappers. Enforces security using parameterized prepared queries.
*   **Includes middleware (`includes/auth_check.php`):** Evaluates active PHP sessions, verifies login roles, regenerates unique session tokens, and executes anti-CSRF token assessments.

---

## 💾 Relational Database Schema

### Table Definitions

#### 1. `users`
Stores administrative staff and teacher account details.
*   `id` (INT, Primary Key, Auto-Increment)
*   `username` (VARCHAR, Unique)
*   `email` (VARCHAR, Unique)
*   `password` (VARCHAR, Bcrypt Hashed)
*   `full_name` (VARCHAR)
*   `role` (ENUM: 'admin', 'teacher')
*   `phone` (VARCHAR)
*   `is_active` (TINYINT)
*   `last_login` (DATETIME)

#### 2. `classes`
Defines standard sections or course streams.
*   `id` (INT, Primary Key)
*   `class_name` (VARCHAR)
*   `section` (VARCHAR)
*   `teacher_id` (INT, Foreign Key referencing `users.id`)

#### 3. `students`
Stores pupil profiles mapped to their corresponding classes.
*   `id` (INT, Primary Key)
*   `student_id` (VARCHAR, Unique code e.g. STU001)
*   `first_name` (VARCHAR)
*   `last_name` (VARCHAR)
*   `class_id` (INT, Foreign Key referencing `classes.id`)
*   `guardian_name` (VARCHAR)
*   `guardian_phone` (VARCHAR)

#### 4. `subjects`
Defines course catalogs.
*   `id` (INT, Primary Key)
*   `subject_name` (VARCHAR)
*   `subject_code` (VARCHAR, Unique)
*   `class_id` (INT, Foreign Key referencing `classes.id`)

#### 5. `attendance`
Logs attendance statuses mapped on class, subject, date, and student attributes.
*   `id` (INT, Primary Key)
*   `student_id` (INT, Foreign Key referencing `students.id`)
*   `class_id` (INT, Foreign Key referencing `classes.id`)
*   `subject_id` (INT, Foreign Key referencing `subjects.id`, Nullable)
*   `date` (DATE)
*   `status` (ENUM: 'present', 'absent', 'late', 'excused')
*   `remarks` (TEXT)
*   `marked_by` (INT, Foreign Key referencing `users.id`)
*   **Unique Constraint Key:** (`student_id`, `class_id`, `subject_id`, `date`) - Enforces database-level duplicate prevention.

#### 6. `settings`
Stores configuration options.
*   `setting_key` (VARCHAR, Unique Primary Key)
*   `setting_value` (TEXT)

---

## 🔌 API Reference Guide

All API endpoints are located within the `api/` directory, require authorization, validate CSRF values on POST actions, and respond with JSON.

### 1. Student Endpoint (`api/students.php`)
*   **GET `?action=get&id={id}`**: Retrieve detailed student profile data.
*   **POST `action=add`**: Insert a new student record. Auto-generates Roll ID.
*   **POST `action=edit`**: Modify details for an existing student.
*   **POST `action=delete`**: Delete student profile.

### 2. Class Endpoint (`api/classes.php`)
*   **GET `?action=get&id={id}`**: Retrieve class details.
*   **POST `action=add`**: Add new class section. Enforces unique name + section name.
*   **POST `action=edit`**: Update class details.
*   **POST `action=delete`**: Delete class.

### 3. Attendance Endpoint (`api/attendance.php`)
*   **GET `?action=fetch_students&class_id={id}&subject_id={id}&date={Y-m-d}`**: Returns class students and matches status values if attendance was marked.
*   **POST `action=save`**: Expects list array under `attendance[student_id][status]`. Saves records transactionally.

### 4. Reports Endpoint (`api/reports.php`)
*   **GET `?action=export&class_id={id}&subject_id={id}&start_date={Y-m-d}&end_date={Y-m-d}`**: Returns a downloadable CSV file containing historical logs.

---

## 🔒 Implemented Security Protocols

*   **Prepared Statements:** All dynamic inputs are parsed via PDO prepared queries to completely block SQL Injection.
*   **Anti-XSS Escaping:** Output renders sanitize all text variables using HTMLSpecialChars.
*   **Token-Based CSRF Shield:** Secure tokens are generated and stored in sessions, and validated for every POST form.
*   **Secure Session Lifecycle:**
    *   HttpOnly and SameSite cookie flags are active.
    *   Session ID is regenerated on login and periodically (every 5 minutes).
    *   Auto-timeout terminates sessions after 30 minutes of idle state.
*   **Password Hashing:** Passwords are encrypted using high-entropy `PASSWORD_BCRYPT` hashes.
