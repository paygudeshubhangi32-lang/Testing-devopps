<?php
/**
 * Student API Endpoint
 */
require_once __DIR__ . '/../includes/auth_check.php';

// Check post/get parameters
$action = sanitize($_REQUEST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get') {
        $id = (int)($_GET['id'] ?? 0);
        $student = dbFetchOne("SELECT * FROM students WHERE id = ?", [$id]);
        if ($student) {
            jsonResponse(['success' => true, 'student' => $student]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Student not found.'], 404);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $gender = sanitize($_POST['gender'] ?? 'male');
        $dob = sanitize($_POST['date_of_birth'] ?? '');
        $classId = (int)($_POST['class_id'] ?? 0);
        $guardianName = trim($_POST['guardian_name'] ?? '');
        $guardianPhone = trim($_POST['guardian_phone'] ?? '');
        
        // Input validation
        if (empty($firstName) || empty($lastName) || $classId <= 0) {
            jsonResponse(['success' => false, 'message' => 'First Name, Last Name and Class are required.'], 400);
        }
        
        if (!empty($email) && !isValidEmail($email)) {
            jsonResponse(['success' => false, 'message' => 'Please provide a valid email address.'], 400);
        }
        
        if ($action === 'add') {
            $studentId = generateStudentId();
            
            dbQuery(
                "INSERT INTO students (student_id, first_name, last_name, email, phone, gender, date_of_birth, class_id, guardian_name, guardian_phone) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$studentId, $firstName, $lastName, $email, $phone, $gender, empty($dob) ? null : $dob, $classId, $guardianName, $guardianPhone]
            );
            
            jsonResponse(['success' => true, 'message' => 'Student added successfully!', 'student_id' => $studentId]);
        } else {
            // Edit
            $student = dbFetchOne("SELECT id FROM students WHERE id = ?", [$id]);
            if (!$student) {
                jsonResponse(['success' => false, 'message' => 'Student not found.'], 404);
            }
            
            dbQuery(
                "UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, gender = ?, date_of_birth = ?, class_id = ?, guardian_name = ?, guardian_phone = ? WHERE id = ?",
                [$firstName, $lastName, $email, $phone, $gender, empty($dob) ? null : $dob, $classId, $guardianName, $guardianPhone, $id]
            );
            
            jsonResponse(['success' => true, 'message' => 'Student updated successfully!']);
        }
    }
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $student = dbFetchOne("SELECT id FROM students WHERE id = ?", [$id]);
        if (!$student) {
            jsonResponse(['success' => false, 'message' => 'Student not found.'], 404);
        }
        
        dbQuery("DELETE FROM students WHERE id = ?", [$id]);
        jsonResponse(['success' => true, 'message' => 'Student deleted successfully.']);
    }
}

jsonResponse(['success' => false, 'message' => 'Invalid Request'], 400);
