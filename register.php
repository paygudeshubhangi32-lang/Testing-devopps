<?php
/**
 * Registration Page
 * Role selection, password strength meter, input validation
 */
require_once __DIR__ . '/config/app.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
        $error = 'All required fields must be filled.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username or email already exists
        $existing = dbFetchOne("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
        if ($existing) {
            $error = 'Username or email already exists.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            dbQuery(
                "INSERT INTO users (username, email, password, full_name, phone, role) VALUES (?, ?, ?, ?, ?, 'teacher')",
                [$username, $email, $hashedPassword, $fullName, $phone]
            );
            setFlashMessage('success', 'Account created successfully! Please login.');
            header('Location: ' . BASE_URL . '/login.php');
            exit;
        }
    }
}

$pageTitle = 'Register';
$pageDescription = 'Create your AttendTrack Pro account';
$showNavbar = false;
$showFooter = false;

include __DIR__ . '/includes/header.php';
?>

<div class="auth-page">
    <div class="auth-card animate-scale-in visible" style="max-width:480px;">
        <div class="auth-header">
            <a href="<?php echo BASE_URL; ?>/" class="text-decoration-none">
                <div class="brand-icon">
                    <i class="bi bi-clipboard-data-fill"></i>
                </div>
            </a>
            <h3>Create Account</h3>
            <p>Join AttendTrack Pro today</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center py-2 px-3 mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <small><?php echo sanitize($error); ?></small>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="needs-validation" novalidate>
            <?php echo csrfField(); ?>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name" 
                       placeholder="Full Name" required 
                       value="<?php echo sanitize($_POST['full_name'] ?? ''); ?>">
                <label for="full_name"><i class="bi bi-person me-1"></i>Full Name</label>
            </div>
            
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Username" required minlength="3"
                               value="<?php echo sanitize($_POST['username'] ?? ''); ?>">
                        <label for="username"><i class="bi bi-at me-1"></i>Username</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Email" required
                               value="<?php echo sanitize($_POST['email'] ?? ''); ?>">
                        <label for="email"><i class="bi bi-envelope me-1"></i>Email</label>
                    </div>
                </div>
            </div>
            
            <div class="form-floating mb-3">
                <input type="tel" class="form-control" id="phone" name="phone" 
                       placeholder="Phone (Optional)"
                       value="<?php echo sanitize($_POST['phone'] ?? ''); ?>">
                <label for="phone"><i class="bi bi-telephone me-1"></i>Phone (Optional)</label>
            </div>
            
            <div class="mb-2">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Password" required minlength="6"
                               data-validate-password="true" onkeyup="updatePasswordStrength(this)"
                               style="border-right:0;">
                        <label for="password"><i class="bi bi-lock me-1"></i>Password</label>
                    </div>
                    <span class="input-group-text password-toggle" style="cursor:pointer;border-left:0;">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="progress" style="height:4px;border-radius:99px;">
                    <div class="progress-bar" id="passwordStrength" role="progressbar" style="width:0%;"></div>
                </div>
                <small class="text-muted" id="passwordStrengthLabel"></small>
            </div>
            
            <div class="mb-3">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                               placeholder="Confirm Password" required data-match-password="password"
                               style="border-right:0;">
                        <label for="confirm_password"><i class="bi bi-lock-fill me-1"></i>Confirm Password</label>
                    </div>
                    <span class="input-group-text password-toggle" style="cursor:pointer;border-left:0;">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>
        </form>
        
        <div class="text-center">
            <small class="text-muted">
                Already have an account? 
                <a href="<?php echo BASE_URL; ?>/login.php" class="fw-semibold">Sign In</a>
            </small>
        </div>
        
        <hr class="my-3">
        
        <div class="text-center">
            <small class="text-muted">
                <a href="<?php echo BASE_URL; ?>/" class="text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Back to Home
                </a>
            </small>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
