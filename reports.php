<?php
/**
 * Reports API Endpoint
 */
require_once __DIR__ . '/../includes/auth_check.php';

$action = sanitize($_REQUEST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'export') {
    $classId = (int)($_GET['class_id'] ?? 0);
    $subjectId = (int)($_GET['subject_id'] ?? 0);
    $startDate = sanitize($_GET['start_date'] ?? '');
    $endDate = sanitize($_GET['end_date'] ?? '');
    
    if ($classId <= 0 || empty($startDate) || empty($endDate)) {
        die('Class ID, Start Date and End Date are required.');
    }
    
    // Fetch records
    $where = "a.class_id = ? AND a.date BETWEEN ? AND ?";
    $params = [$classId, $startDate, $endDate];
    
    if ($subjectId > 0) {
        $where .= " AND a.subject_id = ?";
        $params[] = $subjectId;
    } else {
        $where .= " AND a.subject_id IS NULL";
    }
    
    $records = dbFetchAll("
        SELECT a.date, a.status, a.remarks, s.student_id, s.first_name, s.last_name, 
               c.class_name, c.section, sub.subject_name
        FROM attendance a
        JOIN students s ON a.student_id = s.id
        JOIN classes c ON a.class_id = c.id
        LEFT JOIN subjects sub ON a.subject_id = sub.id
        WHERE $where
        ORDER BY a.date DESC, s.first_name, s.last_name
    ", $params);
    
    // Set headers for download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Attendance_Report_' . date('Ymd_His') . '.csv');
    
    // Create file pointer
    $output = fopen('php://output', 'w');
    
    // Output headers
    fputcsv($output, ['Date', 'Student ID', 'First Name', 'Last Name', 'Class', 'Section', 'Subject', 'Status', 'Remarks']);
    
    // Output data
    foreach ($records as $row) {
        fputcsv($output, [
            $row['date'],
            $row['student_id'],
            $row['first_name'],
            $row['last_name'],
            $row['class_name'],
            $row['section'],
            $row['subject_name'] ?? 'N/A',
            ucfirst($row['status']),
            $row['remarks']
        ]);
    }
    
    fclose($output);
    exit;
}

jsonResponse(['success' => false, 'message' => 'Invalid Request'], 400);
