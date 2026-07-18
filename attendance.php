<?php
/**
 * Attendance API Endpoint
 */
require_once __DIR__ . '/../includes/auth_check.php';

$action = sanitize($_REQUEST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'fetch_students') {
        $classId = (int)($_GET['class_id'] ?? 0);
        $subjectId = (int)($_GET['subject_id'] ?? 0);
        $date = sanitize($_GET['date'] ?? date('Y-m-d'));
        
        if ($classId <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid class ID.'], 400);
        }
        
        // Fetch all active students in class
        $students = dbFetchAll("
            SELECT s.id, s.student_id, s.first_name, s.last_name, s.gender,
                   a.status as attendance_status, a.remarks as attendance_remarks
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id 
                                  AND a.class_id = ? 
                                  AND a.date = ? 
                                  AND (a.subject_id = ? OR (a.subject_id IS NULL AND ? = 0))
            WHERE s.class_id = ? AND s.is_active = 1
            ORDER BY s.first_name, s.last_name
        ", [$classId, $date, $subjectId, $subjectId, $classId]);
        
        jsonResponse(['success' => true, 'students' => $students]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'save') {
        $classId = (int)($_POST['class_id'] ?? 0);
        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $date = sanitize($_POST['date'] ?? '');
        $records = $_POST['attendance'] ?? []; // Array of student_id => [status, remarks]
        
        if ($classId <= 0 || empty($date)) {
            jsonResponse(['success' => false, 'message' => 'Class and Date are required fields.'], 400);
        }
        
        if (!isValidDate($date)) {
            jsonResponse(['success' => false, 'message' => 'Invalid date format.'], 400);
        }
        
        $markedBy = $currentUser['id'];
        $subjectVal = $subjectId > 0 ? $subjectId : null;
        
        // Start transaction
        $pdo = getDBConnection();
        $pdo->beginTransaction();
        
        try {
            foreach ($records as $studentId => $data) {
                $studentId = (int)$studentId;
                $status = sanitize($data['status'] ?? 'present');
                $remarks = trim(sanitize($data['remarks'] ?? ''));
                
                // Use INSERT ON DUPLICATE KEY UPDATE to avoid duplicates
                dbQuery("
                    INSERT INTO attendance (student_id, class_id, subject_id, date, status, remarks, marked_by)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE status = ?, remarks = ?, marked_by = ?, updated_at = NOW()
                ", [$studentId, $classId, $subjectVal, $date, $status, $remarks, $markedBy, $status, $remarks, $markedBy]);
            }
            
            $pdo->commit();
            jsonResponse(['success' => true, 'message' => 'Attendance saved successfully!']);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error saving attendance: " . $e->getMessage());
            jsonResponse(['success' => false, 'message' => 'Failed to save attendance records. Please try again.'], 500);
        }
    }
}

jsonResponse(['success' => false, 'message' => 'Invalid Request'], 400);
