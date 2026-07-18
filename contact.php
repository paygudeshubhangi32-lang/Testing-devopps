<?php
/**
 * Contact Page
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with AttendTrack Pro team';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken()) {
        setFlashMessage('danger', 'Invalid security token.');
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $subject = sanitize($_POST['subject'] ?? '');
        $message = sanitize($_POST['message'] ?? '');
        
        if (!empty($name) && !empty($email) && !empty($message)) {
            $success = 'Thank you for your message! We will get back to you soon.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container position-relative">
        <span class="badge bg-primary bg-opacity-25 px-3 py-2 mb-3" style="color:#818cf8 !important;">
            <i class="bi bi-envelope me-1"></i>Contact
        </span>
        <h1>Get in Touch</h1>
        <p class="mt-2">We'd love to hear from you. Send us a message!</p>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="section">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-1">
                <div class="contact-info-card hover-lift">
                    <div class="contact-icon bg-gradient-primary">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h5 class="fw-bold">Our Location</h5>
                    <p class="text-secondary small mb-0">123 Education Lane<br>Mumbai, Maharashtra 400001</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-2">
                <div class="contact-info-card hover-lift">
                    <div class="contact-icon bg-gradient-success">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5 class="fw-bold">Email Us</h5>
                    <p class="text-secondary small mb-0">admin@attendtrack.com<br>support@attendtrack.com</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 animate-fade-in-up delay-3">
                <div class="contact-info-card hover-lift">
                    <div class="contact-icon bg-gradient-warning">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h5 class="fw-bold">Call Us</h5>
                    <p class="text-secondary small mb-0">+91 98765 43210<br>Mon - Fri: 8am - 5pm</p>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8 animate-fade-in-up">
                <div class="card p-4 p-md-5">
                    <h3 class="fw-bold mb-1">Send a Message</h3>
                    <p class="text-secondary mb-4">Fill out the form below and we'll respond as soon as possible.</p>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <?php echo csrfField(); ?>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required placeholder="John Doe">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required placeholder="john@example.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" class="form-control" name="subject" placeholder="How can we help?">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="message" rows="5" required placeholder="Your message..."></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
