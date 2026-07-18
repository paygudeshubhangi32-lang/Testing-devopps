<?php
/**
 * FAQ Page
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'FAQ';
$pageDescription = 'Frequently Asked Questions about AttendTrack Pro';

include __DIR__ . '/includes/header.php';

$faqs = [
    ['category' => 'General', 'items' => [
        ['q' => 'What is AttendTrack Pro?', 'a' => 'AttendTrack Pro is a modern, web-based attendance management system designed for educational institutions. It helps teachers and administrators efficiently track, manage, and report student attendance.'],
        ['q' => 'Is AttendTrack Pro free to use?', 'a' => 'Yes! AttendTrack Pro is a free, open-source project. You can download, install, and use it without any cost.'],
        ['q' => 'What technology is used?', 'a' => 'AttendTrack Pro is built with PHP, MySQL, Bootstrap 5, and vanilla JavaScript. It runs on XAMPP (Apache + MySQL) and requires no additional frameworks or dependencies.'],
    ]],
    ['category' => 'Getting Started', 'items' => [
        ['q' => 'How do I install AttendTrack Pro?', 'a' => 'Simply place the project folder in your XAMPP htdocs directory, start Apache and MySQL from XAMPP Control Panel, and navigate to http://localhost/shubhangi/ in your browser. The database will be created automatically on first visit.'],
        ['q' => 'What are the default login credentials?', 'a' => 'The default admin credentials are: Username: admin, Password: admin123. For the demo teacher account: Username: teacher, Password: teacher123. We recommend changing these after first login.'],
        ['q' => 'Can I create multiple user accounts?', 'a' => 'Yes! Admins can create additional admin and teacher accounts through the User Management section. Teachers can also self-register through the registration page.'],
    ]],
    ['category' => 'Features', 'items' => [
        ['q' => 'How do I mark attendance?', 'a' => 'Navigate to Dashboard → Mark Attendance. Select a class, subject, and date, then mark each student as Present, Absent, Late, or Excused. Click Save to record the attendance.'],
        ['q' => 'Can I export attendance reports?', 'a' => 'Yes! Go to Reports, filter by class and date range, then click the Export CSV button to download a spreadsheet-compatible file with attendance data.'],
        ['q' => 'Does it support dark mode?', 'a' => 'Yes! Click the moon/sun icon in the navigation bar to toggle between light and dark themes. Your preference is saved automatically.'],
        ['q' => 'Is the system mobile-friendly?', 'a' => 'Absolutely! AttendTrack Pro is fully responsive and works great on desktops, tablets, and mobile phones.'],
    ]],
    ['category' => 'Security', 'items' => [
        ['q' => 'How is my data protected?', 'a' => 'We implement multiple security measures: passwords are bcrypt-hashed, all database queries use prepared statements to prevent SQL injection, CSRF tokens protect forms, and XSS protection is applied to all user inputs.'],
        ['q' => 'Can students access the admin panel?', 'a' => 'No. The admin panel is role-protected. Only authenticated users with admin or teacher roles can access it. Students are managed as data records and do not have login access.'],
    ]],
];
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container position-relative">
        <span class="badge bg-primary bg-opacity-25 px-3 py-2 mb-3" style="color:#818cf8 !important;">
            <i class="bi bi-question-circle me-1"></i>FAQ
        </span>
        <h1>Frequently Asked Questions</h1>
        <p class="mt-2">Find answers to common questions about AttendTrack Pro</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Search -->
                <div class="mb-4 animate-fade-in-up">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="faqSearch" placeholder="Search questions..." onkeyup="filterFAQ(this.value)">
                    </div>
                </div>
                
                <?php foreach ($faqs as $index => $section): ?>
                    <div class="mb-4 animate-fade-in-up delay-<?php echo min($index + 1, 3); ?> faq-section">
                        <h4 class="fw-bold mb-3">
                            <i class="bi bi-bookmark-fill text-primary me-2"></i><?php echo $section['category']; ?>
                        </h4>
                        <div class="accordion" id="faqAccordion<?php echo $index; ?>">
                            <?php foreach ($section['items'] as $i => $faq): ?>
                                <div class="accordion-item faq-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#faq<?php echo $index; ?>_<?php echo $i; ?>">
                                            <?php echo sanitize($faq['q']); ?>
                                        </button>
                                    </h2>
                                    <div id="faq<?php echo $index; ?>_<?php echo $i; ?>" 
                                         class="accordion-collapse collapse" 
                                         data-bs-parent="#faqAccordion<?php echo $index; ?>">
                                        <div class="accordion-body">
                                            <?php echo sanitize($faq['a']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Still need help -->
                <div class="text-center mt-5 animate-fade-in-up">
                    <div class="card p-4 p-md-5" style="background:var(--primary-gradient);color:#fff;border:none;">
                        <h4 class="fw-bold mb-2">Still Have Questions?</h4>
                        <p class="mb-3" style="opacity:0.8;">Can't find what you're looking for? We're here to help!</p>
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="btn btn-light px-4 py-2 fw-semibold" style="border-radius:var(--radius-xl);">
                            <i class="bi bi-envelope me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function filterFAQ(query) {
    query = query.toLowerCase();
    document.querySelectorAll('.faq-item').forEach(function(item) {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(query) ? '' : 'none';
    });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
