<?php
/**
 * Helper Functions
 * Attendance Management System
 */

// =====================================================
// Input Sanitization & Validation
// =====================================================

/**
 * Sanitize input string
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number
 */
function isValidPhone($phone) {
    return preg_match('/^[0-9+\-\s()]{7,20}$/', $phone);
}

/**
 * Validate date format
 */
function isValidDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// =====================================================
// CSRF Protection
// =====================================================

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Get CSRF hidden input field
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token = null) {
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    }
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// =====================================================
// Session & Authentication
// =====================================================

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    static $user = null;
    if ($user === null) {
        $user = dbFetchOne("SELECT * FROM users WHERE id = ? AND is_active = 1", [$_SESSION['user_id']]);
    }
    return $user;
}

/**
 * Check if current user is admin
 */
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

/**
 * Get current user's role
 */
function getUserRole() {
    $user = getCurrentUser();
    return $user ? $user['role'] : null;
}

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// =====================================================
// Date & Time Helpers
// =====================================================

/**
 * Format date for display
 */
function formatDate($date, $format = 'd M Y') {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

/**
 * Format datetime for display
 */
function formatDateTime($datetime, $format = 'd M Y, h:i A') {
    if (empty($datetime)) return '-';
    return date($format, strtotime($datetime));
}

/**
 * Get relative time (e.g., "2 hours ago")
 */
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}

// =====================================================
// Pagination
// =====================================================

/**
 * Generate pagination data
 */
function paginate($totalItems, $currentPage = 1, $perPage = 10) {
    $totalPages = max(1, ceil($totalItems / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    
    return [
        'total' => $totalItems,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages
    ];
}

// =====================================================
// Statistics Helpers
// =====================================================

/**
 * Get total count from a table
 */
function getTotalCount($table, $where = '1=1', $params = []) {
    $result = dbFetchOne("SELECT COUNT(*) as count FROM `$table` WHERE $where", $params);
    return $result ? (int)$result['count'] : 0;
}

/**
 * Get attendance percentage for a student
 */
function getStudentAttendancePercentage($studentId, $classId = null) {
    $where = "student_id = ?";
    $params = [$studentId];
    
    if ($classId) {
        $where .= " AND class_id = ?";
        $params[] = $classId;
    }
    
    $total = getTotalCount('attendance', $where, $params);
    if ($total === 0) return 0;
    
    $present = getTotalCount('attendance', $where . " AND status IN ('present', 'late')", $params);
    return round(($present / $total) * 100, 1);
}

/**
 * Get today's attendance summary
 */
function getTodayAttendanceSummary() {
    $today = date('Y-m-d');
    $total = getTotalCount('attendance', 'date = ?', [$today]);
    $present = getTotalCount('attendance', "date = ? AND status = 'present'", [$today]);
    $absent = getTotalCount('attendance', "date = ? AND status = 'absent'", [$today]);
    $late = getTotalCount('attendance', "date = ? AND status = 'late'", [$today]);
    
    return [
        'total' => $total,
        'present' => $present,
        'absent' => $absent,
        'late' => $late,
        'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0
    ];
}

// =====================================================
// Miscellaneous
// =====================================================

/**
 * Generate a unique student ID
 */
function generateStudentId() {
    $last = dbFetchOne("SELECT student_id FROM students ORDER BY id DESC LIMIT 1");
    if ($last) {
        $num = (int)substr($last['student_id'], 3) + 1;
    } else {
        $num = 1;
    }
    return 'STU' . str_pad($num, 3, '0', STR_PAD_LEFT);
}

/**
 * Get setting value from database
 */
function getSetting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        try {
            $rows = dbFetchAll("SELECT setting_key, setting_value FROM settings");
            $settings = array_column($rows, 'setting_value', 'setting_key');
        } catch (Exception $e) {
            $settings = [];
        }
    }
    return $settings[$key] ?? $default;
}

/**
 * Update a setting value
 */
function updateSetting($key, $value) {
    dbQuery(
        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
         ON DUPLICATE KEY UPDATE setting_value = ?",
        [$key, $value, $value]
    );
}

/**
 * Send JSON response (for AJAX)
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Redirect with optional flash message
 */
function redirect($url, $flashType = null, $flashMessage = null) {
    if ($flashType && $flashMessage) {
        setFlashMessage($flashType, $flashMessage);
    }
    header("Location: $url");
    exit;
}

/**
 * Get status badge HTML
 */
function getStatusBadge($status) {
    $badges = [
        'present' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Present</span>',
        'absent'  => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Absent</span>',
        'late'    => '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Late</span>',
        'excused' => '<span class="badge bg-info"><i class="bi bi-info-circle me-1"></i>Excused</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
