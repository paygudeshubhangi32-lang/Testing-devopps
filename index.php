<?php
/**
 * Landing Page - AttendTrack Pro
 * Modern hero section with features, stats, and CTA
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Home';
$pageDescription = 'AttendTrack Pro - Modern Attendance Management System for Educational Institutions';

// Get some stats for the landing page
$totalStudents = getTotalCount('students', 'is_active = 1');
$totalClasses = getTotalCount('classes', 'is_active = 1');
$totalUsers = getTotalCount('users', 'is_active = 1');

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="animate-fade-in-up">
                    <span class="badge bg-primary bg-opacity-25 text-primary-light px-3 py-2 mb-3" style="color:#818cf8 !important;">
                        <i class="bi bi-stars me-1"></i>Smart Attendance Solution
                    </span>
                    <h1 class="hero-title">
                        Manage Attendance<br>
                        <span class="gradient-text">Effortlessly</span>
                    </h1>
                    <p class="hero-subtitle">
                        A powerful, modern attendance management system designed for educational institutions. 
                        Track, analyze, and report attendance with ease.
                    </p>
                    <div class="hero-actions">
                        <?php if (isLoggedIn()): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-glow">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-glow">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Get Started
                            </a>
                            <a href="#features" class="btn btn-outline-light btn-lg px-4">
                                <i class="bi bi-play-circle me-2"></i>Learn More
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Hero Stats -->
                <div class="hero-stats animate-fade-in-up delay-2">
                    <div class="hero-stat">
                        <div class="hero-stat-value" data-count="<?php echo $totalStudents; ?>">0</div>
                        <div class="hero-stat-label">Students</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value" data-count="<?php echo $totalClasses; ?>">0</div>
                        <div class="hero-stat-label">Classes</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value" data-count="98">0</div>
                        <div class="hero-stat-label">% Uptime</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 hero-illustration">
                <div class="animate-fade-in-up delay-1">
                    <!-- Floating Dashboard Preview Card -->
                    <div class="hero-card-float p-4 mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width:40px;height:40px;background:rgba(16,185,129,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-graph-up-arrow" style="color:#10b981;font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:1.1rem;">Today's Attendance</div>
                                <div style="color:rgba(255,255,255,0.5);font-size:0.8rem;"><?php echo date('d M Y'); ?></div>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="text-center flex-fill" style="background:rgba(16,185,129,0.15);border-radius:12px;padding:0.75rem;">
                                <div style="font-size:1.5rem;font-weight:800;color:#10b981;">85%</div>
                                <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);">Present</div>
                            </div>
                            <div class="text-center flex-fill" style="background:rgba(244,63,94,0.15);border-radius:12px;padding:0.75rem;">
                                <div style="font-size:1.5rem;font-weight:800;color:#f43f5e;">10%</div>
                                <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);">Absent</div>
                            </div>
                            <div class="text-center flex-fill" style="background:rgba(245,158,11,0.15);border-radius:12px;padding:0.75rem;">
                                <div style="font-size:1.5rem;font-weight:800;color:#f59e0b;">5%</div>
                                <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);">Late</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mini floating cards -->
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="hero-card-float delay-1 p-3 text-center">
                                <i class="bi bi-mortarboard-fill mb-2" style="font-size:2rem;color:#818cf8;"></i>
                                <div style="font-weight:700;"><?php echo $totalStudents; ?>+ Students</div>
                                <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">Registered</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-card-float delay-2 p-3 text-center">
                                <i class="bi bi-shield-check mb-2" style="font-size:2rem;color:#10b981;"></i>
                                <div style="font-weight:700;">100% Secure</div>
                                <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">Encrypted Data</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" id="features" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="text-center animate-fade-in-up">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
                <i class="bi bi-lightning-fill me-1"></i>Features
            </span>
            <h2 class="section-title">Everything You Need</h2>
            <p class="section-subtitle">Powerful features designed to make attendance management seamless and efficient.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-1">
                <div class="feature-card hover-lift">
                    <div class="feature-icon bg-gradient-primary">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h5>Smart Attendance</h5>
                    <p>Mark attendance quickly with our intuitive interface. Bulk marking, date selection, and real-time updates.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-2">
                <div class="feature-card hover-lift">
                    <div class="feature-icon bg-gradient-success">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5>Analytics & Reports</h5>
                    <p>Visualize attendance trends with charts, generate reports, and export data to CSV for analysis.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-3">
                <div class="feature-card hover-lift">
                    <div class="feature-icon bg-gradient-warning">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5>Student Management</h5>
                    <p>Complete student profiles with class assignments, contact details, and attendance history tracking.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-4">
                <div class="feature-card hover-lift">
                    <div class="feature-icon bg-gradient-danger">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h5>Secure & Reliable</h5>
                    <p>Role-based access control, encrypted passwords, CSRF protection, and SQL injection prevention.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-5">
                <div class="feature-card hover-lift">
                    <div class="feature-icon bg-gradient-info">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h5>Fully Responsive</h5>
                    <p>Works perfectly on desktop, tablet, and mobile devices. Access from anywhere, anytime.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-6">
                <div class="feature-card hover-lift">
                    <div class="feature-icon" style="background:linear-gradient(135deg, #8b5cf6, #6d28d9);">
                        <i class="bi bi-moon-stars"></i>
                    </div>
                    <h5>Dark Mode</h5>
                    <p>Built-in dark mode with one-click toggle. Easy on the eyes during late-night sessions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #fff;">
    <div class="container text-center">
        <div class="animate-fade-in-up">
            <h2 class="mb-3" style="font-weight:800;font-size:2.25rem;">Ready to Get Started?</h2>
            <p class="mb-4" style="color:rgba(255,255,255,0.8);font-size:1.1rem;max-width:500px;margin:0 auto 2rem;">
                Join hundreds of institutions managing their attendance efficiently with AttendTrack Pro.
            </p>
            <?php if (!isLoggedIn()): ?>
                <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-light btn-lg px-5 hover-lift" style="font-weight:700;border-radius:var(--radius-xl);">
                    <i class="bi bi-rocket-takeoff me-2"></i>Create Free Account
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-light btn-lg px-5 hover-lift" style="font-weight:700;border-radius:var(--radius-xl);">
                    <i class="bi bi-speedometer2 me-2"></i>Open Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
