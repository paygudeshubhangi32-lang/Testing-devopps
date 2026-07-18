<?php
/**
 * Class API Endpoint
 */
require_once __DIR__ . '/../includes/auth_check.php';

$action = sanitize($_REQUEST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get') {
        $id = (int)($_GET['id'] ?? 0);
        $class = dbFetchOne("SELECT * FROM classes WHERE id = ?", [$id]);
        if ($class) {
            jsonResponse(['success' => true, 'class' => $class]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Class not found.'], 404);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $className = trim($_POST['class_name'] ?? '');
        $section = trim($_POST['section'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $teacherId = (int)($_POST['teacher_id'] ?? 0);
        
        if (empty($className)) {
            jsonResponse(['success' => false, 'message' => 'Class Name is required.'], 400);
        }
        
        // Check uniqueness of Class Name + Section
        $checkParams = [$className, $section];
        $checkQuery = "SELECT id FROM classes WHERE class_name = ? AND section = ?";
        if ($action === 'edit') {
            $checkQuery .= " AND id != ?";
            $checkParams[] = $id;
        }
        $existing = dbFetchOne($checkQuery, $checkParams);
        if ($existing) {
            jsonResponse(['success' => false, 'message' => 'A class with this name and section already exists.'], 400);
        }
        
        if ($action === 'add') {
            dbQuery(
                "INSERT INTO classes (class_name, section, description, teacher_id) VALUES (?, ?, ?, ?)",
                [$className, $section, $description, $teacherId > 0 ? $teacherId : null]
            );
            jsonResponse(['success' => true, 'message' => 'Class created successfully!']);
        } else {
            // Edit
            $class = dbFetchOne("SELECT id FROM classes WHERE id = ?", [$id]);
            if (!$class) {
                jsonResponse(['success' => false, 'message' => 'Class not found.'], 404);
            }
            
            dbQuery(
                "UPDATE classes SET class_name = ?, section = ?, description = ?, teacher_id = ? WHERE id = ?",
                [$className, $section, $description, $teacherId > 0 ? $teacherId : null, $id]
            );
            jsonResponse(['success' => true, 'message' => 'Class updated successfully!']);
        }
    }
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $class = dbFetchOne("SELECT id FROM classes WHERE id = ?", [$id]);
        if (!$class) {
            jsonResponse(['success' => false, 'message' => 'Class not found.'], 404);
        }
        
        dbQuery("DELETE FROM classes WHERE id = ?", [$id]);
        jsonResponse(['success' => true, 'message' => 'Class deleted successfully.']);
    }
}

jsonResponse(['success' => false, 'message' => 'Invalid Request'], 400);
