<?php
/**
 * Login Page
 * Glassmorphism card with form validation
 */
require_once __DIR__ . '/config/app.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = dbFetchOne("SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1", [$username, $username]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['last_activity'] = time();
            $_SESSION['last_regeneration'] = time();
            
            // Update last login
            dbQuery("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
            
            setFlashMessage('success', 'Welcome back, ' . $user['full_name'] . '!');
            header('Location: ' . BASE_URL . '/admin/dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$pageTitle = 'Login';
$pageDescription = 'Login to AttendTrack Pro';
$showNavbar = false;
$showFooter = false;

include __DIR__ . '/includes/header.php';
?>

<div class="auth-page">
    <div class="auth-card animate-scale-in visible">
        <div class="auth-header">
            <a href="<?php echo BASE_URL; ?>/" class="text-decoration-none">
                <div class="brand-icon">
                    <i class="bi bi-clipboard-data-fill"></i>
                </div>
            </a>
            <h3>Welcome Back</h3>
            <p>Sign in to your account to continue</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center py-2 px-3 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <small><?php echo sanitize($error); ?></small>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="needs-validation" novalidate>
            <?php echo csrfField(); ?>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Username or Email" required autofocus
                       value="<?php echo sanitize($_POST['username'] ?? ''); ?>">
                <label for="username"><i class="bi bi-person me-1"></i>Username or Email</label>
            </div>
            
            <div class="mb-3">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Password" required style="border-right:0;">
                        <label for="password"><i class="bi bi-lock me-1"></i>Password</label>
                    </div>
                    <span class="input-group-text password-toggle" style="cursor:pointer;border-left:0;">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>
        
        <div class="text-center">
            <small class="text-muted">
                Don't have an account? 
                <a href="<?php echo BASE_URL; ?>/register.php" class="fw-semibold">Create Account</a>
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
