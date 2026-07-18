<?php
/**
 * User Profile Management Page
 */
require_once __DIR__ . '/../includes/auth_check.php';

$pageTitle = 'My Profile';
$pageDescription = 'Manage your account credentials';
$isAdminPage = true;
$showNavbar = false;
$showFooter = false;

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($fullName) || empty($email)) {
        $error = 'Full Name and Email are required fields.';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check email uniqueness
        $existing = dbFetchOne("SELECT id FROM users WHERE email = ? AND id != ?", [$email, $currentUser['id']]);
        if ($existing) {
            $error = 'Email address is already in use by another account.';
        } else {
            // Update profile
            dbQuery(
                "UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?",
                [$fullName, $email, $phone, $currentUser['id']]
            );
            
            // Password update flow if password fields entered
            if (!empty($currentPassword)) {
                if (password_verify($currentPassword, $currentUser['password'])) {
                    if (strlen($newPassword) < 6) {
                        $error = 'New password must be at least 6 characters long.';
                    } elseif ($newPassword !== $confirmPassword) {
                        $error = 'New passwords do not match.';
                    } else {
                        $newHashed = password_hash($newPassword, PASSWORD_BCRYPT);
                        dbQuery("UPDATE users SET password = ? WHERE id = ?", [$newHashed, $currentUser['id']]);
                        $success = 'Profile and password updated successfully!';
                    }
                } else {
                    $error = 'Incorrect current password.';
                }
            } else {
                $success = 'Profile details updated successfully!';
            }
        }
    }
    
    // Refresh cached current user info
    if (empty($error)) {
        // Clear static cache in functions.php helper
        // Since it's static we can just re-assign or let request finish.
        // Session handles the display name
        $_SESSION['full_name'] = $fullName;
    }
}

// Re-fetch user details for updated values
$user = dbFetchOne("SELECT * FROM users WHERE id = ?", [$currentUser['id']]);

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
                    <h4 class="mb-0"><i class="bi bi-person-circle text-primary me-2"></i>My Profile</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
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

        <div class="row g-4 animate-fade-in-up">
            <!-- Profile Info Form -->
            <div class="col-lg-7">
                <div class="card p-4 p-md-5">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Profile Details
                    </h5>
                    <form method="POST" class="needs-validation" novalidate>
                        <?php echo csrfField(); ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control bg-light text-muted" value="@<?php echo sanitize($user['username']); ?>" readonly>
                            <small class="text-muted">Username cannot be changed.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="full_name" value="<?php echo sanitize($user['full_name']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="<?php echo sanitize($user['email']); ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" value="<?php echo sanitize($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <!-- Password Fields -->
                        <h5 class="fw-bold mb-3 border-top pt-4 pb-2 border-bottom">
                            <i class="bi bi-key-fill text-primary me-2"></i>Change Password
                        </h5>
                        <p class="text-secondary small mb-3">Fill this section only if you wish to change your account password.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Password</label>
                            <input type="password" class="form-control" name="current_password" placeholder="Verify identity">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" class="form-control" name="new_password" placeholder="At least 6 characters" minlength="6">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Verify matching password">
                        </div>
                        
                        <button type="submit" class="btn btn-primary px-5 py-2">
                            <i class="bi bi-save me-1"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Profile Summary Card -->
            <div class="col-lg-5">
                <div class="card p-4 text-center">
                    <div class="user-avatar mx-auto mb-3" style="width:96px;height:96px;font-size:3rem;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <h4 class="fw-bold mb-1"><?php echo sanitize($user['full_name']); ?></h4>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 fs-6">
                        Role: <?php echo ucfirst($user['role']); ?>
                    </span>
                    <hr>
                    <div class="text-start">
                        <div class="d-flex justify-content-between mb-2 small border-bottom pb-2">
                            <span class="text-muted">Username:</span>
                            <span class="fw-semibold">@<?php echo sanitize($user['username']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small border-bottom pb-2">
                            <span class="text-muted">Email:</span>
                            <span class="fw-semibold"><?php echo sanitize($user['email']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small border-bottom pb-2">
                            <span class="text-muted">Phone:</span>
                            <span class="fw-semibold"><?php echo sanitize($user['phone'] ?: '-'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small border-bottom pb-2">
                            <span class="text-muted">Member Since:</span>
                            <span class="fw-semibold"><?php echo formatDate($user['created_at']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Last Login:</span>
                            <span class="fw-semibold"><?php echo formatDateTime($user['last_login']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
