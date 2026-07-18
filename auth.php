<?php
/**
 * Auth API Endpoint
 */
require_once __DIR__ . '/../config/app.php';

$action = sanitize($_REQUEST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'check_username') {
        $username = trim($_GET['username'] ?? '');
        if (empty($username)) {
            jsonResponse(['success' => false, 'message' => 'Username empty'], 400);
        }
        
        $existing = dbFetchOne("SELECT id FROM users WHERE username = ?", [$username]);
        jsonResponse(['success' => true, 'available' => !$existing]);
    }
    
    if ($action === 'check_email') {
        $email = trim($_GET['email'] ?? '');
        if (empty($email) || !isValidEmail($email)) {
            jsonResponse(['success' => false, 'message' => 'Invalid email'], 400);
        }
        
        $existing = dbFetchOne("SELECT id FROM users WHERE email = ?", [$email]);
        jsonResponse(['success' => true, 'available' => !$existing]);
    }
}

jsonResponse(['success' => false, 'message' => 'Invalid Request'], 400);
