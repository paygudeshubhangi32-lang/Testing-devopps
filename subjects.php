<?php
/**
 * Subject API Endpoint
 */
require_once __DIR__ . '/../includes/auth_check.php';

$action = sanitize($_REQUEST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get') {
        $id = (int)($_GET['id'] ?? 0);
        $subject = dbFetchOne("SELECT * FROM subjects WHERE id = ?", [$id]);
        if ($subject) {
            jsonResponse(['success' => true, 'subject' => $subject]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Subject not found.'], 404);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $subjectName = trim($_POST['subject_name'] ?? '');
        $subjectCode = trim($_POST['subject_code'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $classId = (int)($_POST['class_id'] ?? 0);
        
        if (empty($subjectName) || empty($subjectCode) || $classId <= 0) {
            jsonResponse(['success' => false, 'message' => 'Subject Name, Subject Code and Class are required.'], 400);
        }
        
        // Check uniqueness of subject code
        $checkParams = [$subjectCode];
        $checkQuery = "SELECT id FROM subjects WHERE subject_code = ?";
        if ($action === 'edit') {
            $checkQuery .= " AND id != ?";
            $checkParams[] = $id;
        }
        $existing = dbFetchOne($checkQuery, $checkParams);
        if ($existing) {
            jsonResponse(['success' => false, 'message' => 'A subject with this subject code already exists.'], 400);
        }
        
        if ($action === 'add') {
            dbQuery(
                "INSERT INTO subjects (subject_name, subject_code, description, class_id) VALUES (?, ?, ?, ?)",
                [$subjectName, $subjectCode, $description, $classId]
            );
            jsonResponse(['success' => true, 'message' => 'Subject created successfully!']);
        } else {
            // Edit
            $subject = dbFetchOne("SELECT id FROM subjects WHERE id = ?", [$id]);
            if (!$subject) {
                jsonResponse(['success' => false, 'message' => 'Subject not found.'], 404);
            }
            
            dbQuery(
                "UPDATE subjects SET subject_name = ?, subject_code = ?, description = ?, class_id = ? WHERE id = ?",
                [$subjectName, $subjectCode, $description, $classId, $id]
            );
            jsonResponse(['success' => true, 'message' => 'Subject updated successfully!']);
        }
    }
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $subject = dbFetchOne("SELECT id FROM subjects WHERE id = ?", [$id]);
        if (!$subject) {
            jsonResponse(['success' => false, 'message' => 'Subject not found.'], 404);
        }
        
        dbQuery("DELETE FROM subjects WHERE id = ?", [$id]);
        jsonResponse(['success' => true, 'message' => 'Subject deleted successfully.']);
    }
}

jsonResponse(['success' => false, 'message' => 'Invalid Request'], 400);
