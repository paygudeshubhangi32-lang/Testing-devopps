<?php
/**
 * Application Settings Page (Admin only)
 */
require_once __DIR__ . '/../includes/auth_check.php';

if (!isAdmin()) {
    setFlashMessage('danger', 'Unauthorized access.');
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$pageTitle = 'Settings';
$pageDescription = 'Configure system settings';
$isAdminPage = true;
$showNavbar = false;
$showFooter = false;

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = $_POST['settings'] ?? [];
    
    try {
        foreach ($settings as $key => $val) {
            updateSetting(sanitize($key), sanitize($val));
        }
        $success = 'Settings updated successfully!';
    } catch (Exception $e) {
        $error = 'Failed to save settings: ' . $e->getMessage();
    }
}

// Fetch all settings
$siteName = getSetting('site_name', 'AttendTrack Pro');
$siteDesc = getSetting('site_description', 'Modern Attendance Management System');
$adminEmail = getSetting('admin_email', 'admin@attendance.com');
$timezone = getSetting('timezone', 'Asia/Kolkata');
$startTime = getSetting('attendance_start_time', '08:00');
$endTime = getSetting('attendance_end_time', '17:00');
$lateThreshold = getSetting('late_threshold_minutes', '15');
$minAttendance = getSetting('min_attendance_percentage', '75');

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-layout">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-content">
        <!-- Page Header -->
        <header class="page-header animate-fade-in">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h4 class="mb-0"><i class="bi bi-gear-fill text-primary me-2"></i>Settings</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Message Alerts -->
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 px-3 mb-3"><?php echo sanitize($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success py-2 px-3 mb-3"><?php echo sanitize($success); ?></div>
        <?php endif; ?>

        <!-- Settings Form Card -->
        <div class="card p-4 p-md-5 animate-fade-in-up">
            <form method="POST" class="needs-validation" novalidate>
                <?php echo csrfField(); ?>
                
                <!-- General Section -->
                <h5 class="fw-bold mb-3 border-bottom pb-2">
                    <i class="bi bi-sliders text-primary me-2"></i>General Config
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Site Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="settings[site_name]" value="<?php echo sanitize($siteName); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Admin Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="settings[admin_email]" value="<?php echo sanitize($adminEmail); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Site Description</label>
                        <textarea class="form-control" name="settings[site_description]" rows="2"><?php echo sanitize($siteDesc); ?></textarea>
                    </div>
                </div>

                <!-- Attendance Rules -->
                <h5 class="fw-bold mb-3 border-bottom pb-2">
                    <i class="bi bi-calendar-check text-primary me-2"></i>Attendance Configurations
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-semibold">Day Starts At</label>
                        <input type="time" class="form-control" name="settings[attendance_start_time]" value="<?php echo $startTime; ?>">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-semibold">Day Ends At</label>
                        <input type="time" class="form-control" name="settings[attendance_end_time]" value="<?php echo $endTime; ?>">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-semibold">Late Mark Threshold (Mins)</label>
                        <input type="number" class="form-control" name="settings[late_threshold_minutes]" value="<?php echo $lateThreshold; ?>" min="0">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-semibold">Minimum Attendance %</label>
                        <input type="number" class="form-control" name="settings[min_attendance_percentage]" value="<?php echo $minAttendance; ?>" min="0" max="100">
                    </div>
                </div>

                <!-- Technical Settings -->
                <h5 class="fw-bold mb-3 border-bottom pb-2">
                    <i class="bi bi-clock text-primary me-2"></i>Timezone settings
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Timezone</label>
                        <select class="form-select" name="settings[timezone]">
                            <option value="Asia/Kolkata" <?php echo $timezone === 'Asia/Kolkata' ? 'selected' : ''; ?>>Asia/Kolkata (IST)</option>
                            <option value="UTC" <?php echo $timezone === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                            <option value="America/New_York" <?php echo $timezone === 'America/New_York' ? 'selected' : ''; ?>>America/New_York (EST)</option>
                            <option value="Europe/London" <?php echo $timezone === 'Europe/London' ? 'selected' : ''; ?>>Europe/London (GMT)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="bi bi-save-fill me-2"></i>Save Configuration
                </button>
            </form>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
