<?php
/**
 * Admin Dashboard
 * Attendance Management System
 */
require_once __DIR__ . '/../includes/auth_check.php';

$pageTitle = 'Dashboard';
$pageDescription = 'AttendTrack Pro Dashboard';
$isAdminPage = true;
$showNavbar = false;
$showFooter = false;

// Fetch stats
$totalStudents = getTotalCount('students', 'is_active = 1');
$totalClasses = getTotalCount('classes', 'is_active = 1');
$totalSubjects = getTotalCount('subjects', 'is_active = 1');
$totalTeachers = getTotalCount('users', "role = 'teacher' AND is_active = 1");

// Today's attendance summary
$todaySummary = getTodayAttendanceSummary();

// Fetch attendance trend for the last 7 days with attendance
$trendData = dbFetchAll("
    SELECT date,
           SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
           SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
           SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
           SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused
    FROM attendance
    GROUP BY date
    ORDER BY date DESC
    LIMIT 7
");
$trendData = array_reverse($trendData);

// Fetch class-wise attendance for today or overall
$classStats = dbFetchAll("
    SELECT c.class_name, c.section,
           ROUND(SUM(CASE WHEN a.status IN ('present', 'late') THEN 1 ELSE 0 END) / COUNT(a.id) * 100, 1) as percentage
    FROM classes c
    JOIN attendance a ON c.id = a.class_id
    GROUP BY c.id
    ORDER BY percentage DESC
    LIMIT 5
");

// Fetch recent activity
$recentActivity = dbFetchAll("
    SELECT a.date, a.status, s.first_name, s.last_name, c.class_name, c.section, u.full_name as marked_by
    FROM attendance a
    JOIN students s ON a.student_id = s.id
    JOIN classes c ON a.class_id = c.id
    LEFT JOIN users u ON a.marked_by = u.id
    ORDER BY a.created_at DESC
    LIMIT 5
");

// Prepare charts data for frontend
$labels = [];
$presents = [];
$absents = [];
$lates = [];

foreach ($trendData as $row) {
    $labels[] = formatDate($row['date'], 'd M');
    $presents[] = (int)$row['present'];
    $absents[] = (int)$row['absent'];
    $lates[] = (int)$row['late'];
}

// Distribution Data
$distData = dbFetchOne("
    SELECT 
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
        SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
        SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused
    FROM attendance
");

$distPresent = (int)($distData['present'] ?? 0);
$distAbsent = (int)($distData['absent'] ?? 0);
$distLate = (int)($distData['late'] ?? 0);
$distExcused = (int)($distData['excused'] ?? 0);

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-layout">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-content">
        <!-- Top Bar / Navbar for mobile -->
        <header class="page-header">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h4 class="mb-0">Dashboard</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Theme toggle in dashboard header -->
                <button class="btn btn-icon theme-toggle" id="themeToggleDashboard" title="Toggle Dark Mode">
                    <i class="bi bi-moon-fill" id="themeIconDashboard"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-icon user-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar-sm">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                        <span class="d-none d-lg-inline ms-1"><?php echo sanitize($currentUser['full_name']); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                        <li>
                            <div class="dropdown-header">
                                <strong><?php echo sanitize($currentUser['full_name']); ?></strong>
                                <br><small class="text-muted"><?php echo ucfirst($currentUser['role']); ?></small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <?php if (isAdmin()): ?>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Stats Overview Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6 animate-scale-in visible">
                <div class="stat-card bg-gradient-primary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="stat-label">Total Students</span>
                            <h3 class="stat-value mt-1" data-count="<?php echo $totalStudents; ?>">0</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    </div>
                    <div class="small"><i class="bi bi-arrow-up-right me-1"></i>Active Students</div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 animate-scale-in visible delay-1">
                <div class="stat-card bg-gradient-success">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="stat-label">Today's Attendance</span>
                            <h3 class="stat-value mt-1"><?php echo $todaySummary['percentage']; ?>%</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <div class="small">
                        Present: <strong><?php echo $todaySummary['present']; ?></strong> | 
                        Absent: <strong><?php echo $todaySummary['absent']; ?></strong>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 animate-scale-in visible delay-2">
                <div class="stat-card bg-gradient-warning">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="stat-label">Total Classes</span>
                            <h3 class="stat-value mt-1" data-count="<?php echo $totalClasses; ?>">0</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                    <div class="small"><i class="bi bi-arrow-up-right me-1"></i>Active Classes</div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 animate-scale-in visible delay-3">
                <div class="stat-card bg-gradient-info">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="stat-label">Subjects</span>
                            <h3 class="stat-value mt-1" data-count="<?php echo $totalSubjects; ?>">0</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>
                    <div class="small"><i class="bi bi-arrow-up-right me-1"></i>Total Subjects</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8 animate-fade-in-up">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Attendance Trend (Last 7 Records)</h5>
                        <a href="<?php echo BASE_URL; ?>/admin/reports.php" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-graph-up me-1"></i>Full Report
                        </a>
                    </div>
                    <div style="height: 320px; position: relative;">
                        <canvas id="attendanceTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 animate-fade-in-up delay-1">
                <div class="card h-100 p-4">
                    <h5 class="fw-bold mb-4">Overall Distribution</h5>
                    <div style="height: 240px; position: relative;" class="d-flex align-items-center justify-content-center">
                        <canvas id="attendanceDistChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <span class="text-secondary small">Based on all stored attendance logs.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity & Class performance row -->
        <div class="row g-4">
            <!-- Recent Activity -->
            <div class="col-lg-6 animate-fade-in-up">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Recent Activity Log</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentActivity)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No activity logged yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentActivity as $activity): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">
                                                    <?php echo sanitize($activity['first_name'] . ' ' . $activity['last_name']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo sanitize($activity['class_name'] . ' - ' . $activity['section']); ?>
                                            </td>
                                            <td>
                                                <?php echo formatDate($activity['date']); ?>
                                            </td>
                                            <td>
                                                <?php echo getStatusBadge($activity['status']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Class Performance -->
            <div class="col-lg-6 animate-fade-in-up delay-1">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Top Performing Classes</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Average Attendance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($classStats)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No stats available.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($classStats as $class): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">
                                                    <?php echo sanitize($class['class_name'] . ' - ' . $class['section']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: <?php echo $class['percentage']; ?>%"></div>
                                                    </div>
                                                    <span class="fw-bold"><?php echo $class['percentage']; ?>%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($class['percentage'] >= 85): ?>
                                                    <span class="badge bg-success">Excellent</span>
                                                <?php elseif ($class['percentage'] >= 75): ?>
                                                    <span class="badge bg-warning text-dark">Good</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Critical</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Confirm Delete Modal (Generic helper modal) -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3" style="font-size: 3rem;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <p class="text-secondary small confirm-message">This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4 confirm-delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Theme Toggle on Dashboard specifically
        const dashToggle = document.getElementById('themeToggleDashboard');
        const dashIcon = document.getElementById('themeIconDashboard');
        
        if (dashToggle && dashIcon) {
            const getSavedTheme = () => localStorage.getItem('attendtrack-theme') || 'light';
            const updateDashIcon = (theme) => {
                dashIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            };
            
            updateDashIcon(getSavedTheme());
            
            dashToggle.addEventListener('click', function() {
                const current = document.documentElement.getAttribute('data-bs-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', next);
                localStorage.setItem('attendtrack-theme', next);
                updateDashIcon(next);
                
                // Keep the top menu toggle synchronized if it exists
                const mainIcon = document.getElementById('themeIcon');
                if (mainIcon) {
                    mainIcon.className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
                }
            });
        }
        
        // Pass data to Chart.js charts
        const chartData = {
            trend: {
                labels: <?php echo json_encode($labels); ?>,
                present: <?php echo json_encode($presents); ?>,
                absent: <?php echo json_encode($absents); ?>,
                late: <?php echo json_encode($lates); ?>
            },
            distribution: {
                present: <?php echo $distPresent; ?>,
                absent: <?php echo $distAbsent; ?>,
                late: <?php echo $distLate; ?>,
                excused: <?php echo $distExcused; ?>
            }
        };
        
        initDashboardCharts(chartData);
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
