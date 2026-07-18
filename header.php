<?php
/**
 * Header Component
 * Includes HTML head, meta tags, CSS, and responsive navbar
 * 
 * Usage: Set $pageTitle, $pageDescription, $bodyClass before including
 */

if (!isset($pageTitle)) $pageTitle = 'AttendTrack Pro';
if (!isset($pageDescription)) $pageDescription = 'Modern Attendance Management System';
if (!isset($bodyClass)) $bodyClass = '';
if (!isset($showNavbar)) $showNavbar = true;
if (!isset($isAdminPage)) $isAdminPage = false;

$siteName = defined('APP_NAME') ? APP_NAME : 'AttendTrack Pro';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo sanitize($pageDescription); ?>">
    <meta name="author" content="<?php echo defined('APP_AUTHOR') ? APP_AUTHOR : 'Shubhangi'; ?>">
    <meta name="theme-color" content="#4f46e5">
    <title><?php echo sanitize($pageTitle) . ' | ' . $siteName; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📋</text></svg>">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- DataTables CSS (admin pages only) -->
    <?php if ($isAdminPage): ?>
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Custom CSS -->
    <link href="<?php echo ASSETS_URL; ?>/css/style.css" rel="stylesheet">
    <link href="<?php echo ASSETS_URL; ?>/css/dark-mode.css" rel="stylesheet">
    <link href="<?php echo ASSETS_URL; ?>/css/animations.css" rel="stylesheet">
    <link href="<?php echo ASSETS_URL; ?>/css/loader.css" rel="stylesheet">
    <link href="<?php echo ASSETS_URL; ?>/css/responsive.css" rel="stylesheet">
</head>
<body class="<?php echo $bodyClass; ?>">

<!-- Loader -->
<?php include __DIR__ . '/loader.php'; ?>

<!-- Toast Container -->
<?php include __DIR__ . '/toast.php'; ?>

<?php if ($showNavbar): ?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top glass-navbar" id="mainNavbar">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>/">
            <div class="brand-icon me-2">
                <i class="bi bi-clipboard-data-fill"></i>
            </div>
            <span class="brand-text"><?php echo $siteName; ?></span>
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/about.php">
                        <i class="bi bi-info-circle me-1"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/contact.php">
                        <i class="bi bi-envelope me-1"></i>Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/faq.php">
                        <i class="bi bi-question-circle me-1"></i>FAQ
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/admin/dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <!-- Right side -->
            <div class="d-flex align-items-center gap-2">
                <!-- Dark Mode Toggle -->
                <button class="btn btn-icon theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
                
                <?php if (isLoggedIn()): ?>
                    <?php $user = getCurrentUser(); ?>
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-icon user-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar-sm">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                            <span class="d-none d-lg-inline ms-1"><?php echo sanitize($user['full_name']); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                            <li>
                                <div class="dropdown-header">
                                    <strong><?php echo sanitize($user['full_name']); ?></strong>
                                    <br><small class="text-muted"><?php echo ucfirst($user['role']); ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <?php if (isAdmin()): ?>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-outline-primary btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-person-plus me-1"></i>Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>

<!-- Flash Messages -->
<?php
$flash = getFlashMessage();
if ($flash):
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('<?php echo $flash['message']; ?>', '<?php echo $flash['type']; ?>');
    });
</script>
<?php endif; ?>
