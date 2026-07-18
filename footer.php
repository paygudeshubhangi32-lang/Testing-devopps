<?php
/**
 * Footer Component
 * Professional footer and script includes
 */

$siteName = defined('APP_NAME') ? APP_NAME : 'AttendTrack Pro';
$year = defined('APP_YEAR') ? APP_YEAR : date('Y');
if (!isset($isAdminPage)) $isAdminPage = false;
if (!isset($showFooter)) $showFooter = true;
?>

<?php if ($showFooter && !$isAdminPage): ?>
<!-- Footer -->
<footer class="site-footer">
    <div class="footer-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path d="M0,40 C150,80 350,0 500,40 C650,80 800,20 1000,50 C1200,80 1350,30 1440,60 L1440,100 L0,100 Z"></path>
        </svg>
    </div>
    <div class="footer-content">
        <div class="container">
            <div class="row g-4">
                <!-- Brand Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <div class="d-flex align-items-center mb-3">
                            <div class="brand-icon me-2">
                                <i class="bi bi-clipboard-data-fill"></i>
                            </div>
                            <h5 class="mb-0"><?php echo $siteName; ?></h5>
                        </div>
                        <p class="text-muted-footer">A modern, efficient, and secure attendance management system designed for educational institutions.</p>
                        <div class="social-links">
                            <a href="#" class="social-link" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="social-link" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link" title="GitHub"><i class="bi bi-github"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-heading">Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/"><i class="bi bi-chevron-right"></i>Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/about.php"><i class="bi bi-chevron-right"></i>About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/contact.php"><i class="bi bi-chevron-right"></i>Contact</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/faq.php"><i class="bi bi-chevron-right"></i>FAQ</a></li>
                    </ul>
                </div>
                
                <!-- Features -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Features</h6>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/login.php"><i class="bi bi-chevron-right"></i>Attendance Tracking</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/login.php"><i class="bi bi-chevron-right"></i>Analytics & Reports</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/login.php"><i class="bi bi-chevron-right"></i>Student Management</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/login.php"><i class="bi bi-chevron-right"></i>Class Management</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Contact Info</h6>
                    <ul class="footer-contact">
                        <li><i class="bi bi-geo-alt"></i><span>123 Education Lane, Mumbai, India</span></li>
                        <li><i class="bi bi-envelope"></i><span>admin@attendtrack.com</span></li>
                        <li><i class="bi bi-telephone"></i><span>+91 98765 43210</span></li>
                        <li><i class="bi bi-clock"></i><span>Mon - Fri: 8:00 AM - 5:00 PM</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo $year; ?> <?php echo $siteName; ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> by <?php echo defined('APP_AUTHOR') ? APP_AUTHOR : 'Shubhangi'; ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap 5.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js (admin pages) -->
<?php if ($isAdminPage): ?>
<!-- jQuery (must load before DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<?php endif; ?>

<!-- Custom JS -->
<script src="<?php echo ASSETS_URL; ?>/js/dark-mode.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/toast.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/loader.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/validation.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/app.js"></script>

<?php if ($isAdminPage): ?>
<script src="<?php echo ASSETS_URL; ?>/js/charts.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/datatables.js"></script>
<?php endif; ?>

</body>
</html>
