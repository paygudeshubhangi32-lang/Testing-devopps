<?php
/**
 * Admin Sidebar Component
 * Collapsible sidebar with Bootstrap Icons and active state highlighting
 */

$currentPage = basename($_SERVER['PHP_SELF']);
$user = getCurrentUser();

function sidebarActive($page) {
    global $currentPage;
    return $currentPage === $page ? 'active' : '';
}
?>

<!-- Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-clipboard-data-fill"></i>
            </div>
            <span class="brand-text"><?php echo defined('APP_NAME') ? APP_NAME : 'AttendTrack Pro'; ?></span>
        </a>
        <button class="btn btn-icon sidebar-close d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    
    <!-- User Profile Card -->
    <div class="sidebar-user">
        <div class="user-avatar">
            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
        </div>
        <div class="user-info">
            <h6><?php echo sanitize($user['full_name']); ?></h6>
            <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'primary' : 'info'; ?>">
                <i class="bi bi-<?php echo $user['role'] === 'admin' ? 'shield-check' : 'person-badge'; ?> me-1"></i>
                <?php echo ucfirst($user['role']); ?>
            </span>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-section-title">Main</span>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="sidebar-link <?php echo sidebarActive('dashboard.php'); ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        
        <div class="nav-section">
            <span class="nav-section-title">Management</span>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/admin/students.php" class="sidebar-link <?php echo sidebarActive('students.php'); ?>">
            <i class="bi bi-mortarboard"></i>
            <span>Students</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/admin/classes.php" class="sidebar-link <?php echo sidebarActive('classes.php'); ?>">
            <i class="bi bi-building"></i>
            <span>Classes</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/admin/subjects.php" class="sidebar-link <?php echo sidebarActive('subjects.php'); ?>">
            <i class="bi bi-book"></i>
            <span>Subjects</span>
        </a>
        
        <div class="nav-section">
            <span class="nav-section-title">Attendance</span>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/admin/attendance.php" class="sidebar-link <?php echo sidebarActive('attendance.php'); ?>">
            <i class="bi bi-calendar-check"></i>
            <span>Mark Attendance</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/admin/reports.php" class="sidebar-link <?php echo sidebarActive('reports.php'); ?>">
            <i class="bi bi-graph-up"></i>
            <span>Reports</span>
        </a>
        
        <?php if (isAdmin()): ?>
        <div class="nav-section">
            <span class="nav-section-title">Administration</span>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/admin/users.php" class="sidebar-link <?php echo sidebarActive('users.php'); ?>">
            <i class="bi bi-people"></i>
            <span>Users</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/admin/settings.php" class="sidebar-link <?php echo sidebarActive('settings.php'); ?>">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
        <?php endif; ?>
        
        <div class="nav-section">
            <span class="nav-section-title">Account</span>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/admin/profile.php" class="sidebar-link <?php echo sidebarActive('profile.php'); ?>">
            <i class="bi bi-person-circle"></i>
            <span>My Profile</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/" class="sidebar-link">
            <i class="bi bi-house"></i>
            <span>View Site</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/logout.php" class="sidebar-link text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <small>v<?php echo defined('APP_VERSION') ? APP_VERSION : '1.0.0'; ?></small>
    </div>
</aside>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
