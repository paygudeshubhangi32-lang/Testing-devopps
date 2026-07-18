<?php
/**
 * About Page
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'About Us';
$pageDescription = 'Learn about AttendTrack Pro and our mission';

include __DIR__ . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container position-relative">
        <span class="badge bg-primary bg-opacity-25 px-3 py-2 mb-3" style="color:#818cf8 !important;">
            <i class="bi bi-info-circle me-1"></i>About Us
        </span>
        <h1>About AttendTrack Pro</h1>
        <p class="mt-2">Building better attendance management for educational institutions</p>
    </div>
</section>

<!-- Mission Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-slide-left">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">Our Mission</span>
                <h2 style="font-weight:800;">Simplifying Attendance for Everyone</h2>
                <div class="divider" style="margin-left:0;"></div>
                <p class="text-secondary mt-3">
                    AttendTrack Pro was born from a simple idea: attendance management shouldn't be complicated. 
                    Our platform provides educational institutions with a modern, efficient, and secure way to 
                    track and manage student attendance.
                </p>
                <p class="text-secondary">
                    We believe that technology should simplify processes, not complicate them. That's why we've 
                    designed AttendTrack Pro to be intuitive, fast, and reliable — so teachers can focus on what 
                    matters most: teaching.
                </p>
                <div class="row g-3 mt-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:40px;height:40px;background:rgba(79,70,229,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                            </div>
                            <span class="fw-semibold">Easy to Use</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:40px;height:40px;background:rgba(16,185,129,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-shield-check text-success"></i>
                            </div>
                            <span class="fw-semibold">Fully Secure</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:40px;height:40px;background:rgba(245,158,11,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-lightning-fill text-warning"></i>
                            </div>
                            <span class="fw-semibold">Blazing Fast</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:40px;height:40px;background:rgba(6,182,212,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-phone text-info"></i>
                            </div>
                            <span class="fw-semibold">Responsive</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 animate-slide-right">
                <div class="card card-glass p-4">
                    <div class="row g-4 text-center">
                        <div class="col-6">
                            <div class="counter" data-count="500">0</div>
                            <small class="text-secondary">Students Tracked</small>
                        </div>
                        <div class="col-6">
                            <div class="counter" data-count="50">0</div>
                            <small class="text-secondary">Classes Managed</small>
                        </div>
                        <div class="col-6">
                            <div class="counter" data-count="99">0</div>
                            <small class="text-secondary">% Uptime</small>
                        </div>
                        <div class="col-6">
                            <div class="counter" data-count="24">0</div>
                            <small class="text-secondary">7 Support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="text-center animate-fade-in-up mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">Our Team</span>
            <h2 class="section-title">Meet the Team</h2>
            <p class="section-subtitle">The passionate people behind AttendTrack Pro</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-1">
                <div class="card team-card hover-lift">
                    <div class="team-avatar"><i class="bi bi-person-fill"></i></div>
                    <h5 class="fw-bold">Shubhangi</h5>
                    <p class="text-primary fw-semibold mb-2">Lead Developer</p>
                    <p class="text-secondary small">Full-stack developer with a passion for building elegant and efficient web applications.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-2">
                <div class="card team-card hover-lift">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#10b981,#059669);"><i class="bi bi-palette-fill"></i></div>
                    <h5 class="fw-bold">Design Team</h5>
                    <p class="text-success fw-semibold mb-2">UI/UX Design</p>
                    <p class="text-secondary small">Creating beautiful, intuitive interfaces that users love to interact with.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-3">
                <div class="card team-card hover-lift">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);"><i class="bi bi-database-fill"></i></div>
                    <h5 class="fw-bold">Backend Team</h5>
                    <p class="text-warning fw-semibold mb-2">Database & Security</p>
                    <p class="text-secondary small">Ensuring data integrity, security, and optimal performance for all operations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
