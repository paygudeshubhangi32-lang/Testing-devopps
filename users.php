<?php
/**
 * User/Staff Management Page (Admin only)
 */
require_once __DIR__ . '/../includes/auth_check.php';

// Access Control
if (!isAdmin()) {
    setFlashMessage('danger', 'Unauthorized access. Only system administrators can manage users.');
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$pageTitle = 'Manage System Users';
$pageDescription = 'Manage admin and teacher credentials and permissions';
$isAdminPage = true;
$showNavbar = false;
$showFooter = false;

// Handle CRUD operations
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = sanitize($_POST['action'] ?? '');
    
    if ($action === 'add' || $action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $fullName = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = sanitize($_POST['role'] ?? 'teacher');
        $password = $_POST['password'] ?? '';
        
        if (empty($fullName) || empty($username) || empty($email)) {
            $error = 'All primary details are required.';
        } elseif (!isValidEmail($email)) {
            $error = 'Please enter a valid email address.';
        } else {
            // Check unique constraints
            $checkParams = [$username, $email];
            $checkQuery = "SELECT id FROM users WHERE (username = ? OR email = ?)";
            if ($action === 'edit') {
                $checkQuery .= " AND id != ?";
                $checkParams[] = $id;
            }
            $existing = dbFetchOne($checkQuery, $checkParams);
            
            if ($existing) {
                $error = 'Username or email already assigned to another user.';
            } else {
                if ($action === 'add') {
                    if (empty($password)) {
                        $error = 'Password is required for new accounts.';
                    } else {
                        $hashed = password_hash($password, PASSWORD_BCRYPT);
                        dbQuery(
                            "INSERT INTO users (username, email, password, full_name, role, phone) VALUES (?, ?, ?, ?, ?, ?)",
                            [$username, $email, $hashed, $fullName, $role, $phone]
                        );
                        $success = 'User account created successfully.';
                    }
                } else {
                    // Update
                    $user = dbFetchOne("SELECT id FROM users WHERE id = ?", [$id]);
                    if ($user) {
                        if (!empty($password)) {
                            // Update password as well
                            $hashed = password_hash($password, PASSWORD_BCRYPT);
                            dbQuery(
                                "UPDATE users SET username = ?, email = ?, password = ?, full_name = ?, role = ?, phone = ? WHERE id = ?",
                                [$username, $email, $hashed, $fullName, $role, $phone, $id]
                            );
                        } else {
                            dbQuery(
                                "UPDATE users SET username = ?, email = ?, full_name = ?, role = ?, phone = ? WHERE id = ?",
                                [$username, $email, $fullName, $role, $phone, $id]
                            );
                        }
                        $success = 'User account updated successfully.';
                    } else {
                        $error = 'User not found.';
                    }
                }
            }
        }
    }
    
    if ($action === 'toggle_status') {
        $id = (int)($_POST['id'] ?? 0);
        // Prevent disabling current admin user
        if ($id === $currentUser['id']) {
            $error = 'You cannot deactivate your own account.';
        } else {
            $user = dbFetchOne("SELECT is_active FROM users WHERE id = ?", [$id]);
            if ($user) {
                $newStatus = $user['is_active'] ? 0 : 1;
                dbQuery("UPDATE users SET is_active = ? WHERE id = ?", [$newStatus, $id]);
                $success = 'User status updated successfully.';
            } else {
                $error = 'User not found.';
            }
        }
    }
}

// Fetch users
$users = dbFetchAll("SELECT * FROM users ORDER BY id DESC");

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
                    <h4 class="mb-0"><i class="bi bi-people-fill text-primary me-2"></i>System Users</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddUserModal()">
                <i class="bi bi-person-plus-fill me-1"></i>Add User
            </button>
        </header>

        <!-- Message Alerts -->
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 px-3 mb-3"><?php echo sanitize($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success py-2 px-3 mb-3"><?php echo sanitize($success); ?></div>
        <?php endif; ?>

        <!-- Users Table Card -->
        <div class="card p-4 animate-fade-in-up">
            <div class="table-responsive">
                <table class="table table-hover align-middle data-table">
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th class="text-end no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $usr): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar-sm">
                                            <?php echo strtoupper(substr($usr['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo sanitize($usr['full_name']); ?></div>
                                            <small class="text-muted"><?php echo sanitize($usr['email']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">@<?php echo sanitize($usr['username']); ?></span></td>
                                <td>
                                    <span class="badge bg-<?php echo $usr['role'] === 'admin' ? 'primary' : 'info'; ?>">
                                        <?php echo ucfirst($usr['role']); ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?php echo formatDateTime($usr['last_login']); ?></small></td>
                                <td>
                                    <?php if ($usr['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Suspended</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end no-print">
                                    <button class="btn btn-icon btn-sm" onclick="editUser(<?php echo htmlspecialchars(json_encode($usr)); ?>)" data-bs-toggle="tooltip" title="Edit User">
                                        <i class="bi bi-pencil-fill text-warning"></i>
                                    </button>
                                    
                                    <?php if ($usr['id'] !== $currentUser['id']): ?>
                                        <form method="POST" action="" style="display:inline-block;">
                                            <?php echo csrfField(); ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?php echo $usr['id']; ?>">
                                            <button type="submit" class="btn btn-icon btn-sm ms-1" data-bs-toggle="tooltip" title="<?php echo $usr['is_active'] ? 'Suspend User' : 'Activate User'; ?>">
                                                <i class="bi bi-<?php echo $usr['is_active'] ? 'shield-slash-fill text-danger' : 'shield-fill-check text-success'; ?>"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="userModalLabel">Add User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm" method="POST" action="" class="needs-validation" novalidate>
                <?php echo csrfField(); ?>
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="userId" value="">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" id="fullNameField" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="usernameField" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Role</label>
                            <select class="form-select" name="role" id="roleField">
                                <option value="teacher">Teacher</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="emailField" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="tel" class="form-control" name="phone" id="phoneField">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger" id="pwdAsterisk">*</span></label>
                        <input type="password" class="form-control" name="password" id="passwordField" required placeholder="Choose account password">
                        <small class="text-muted" id="pwdHelp" style="display:none;">Leave blank to keep existing password.</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    
    function openAddUserModal() {
        document.getElementById('userModalLabel').textContent = 'Create User Account';
        document.getElementById('formAction').value = 'add';
        document.getElementById('userId').value = '';
        document.getElementById('userForm').reset();
        document.getElementById('passwordField').required = true;
        document.getElementById('pwdAsterisk').style.display = '';
        document.getElementById('pwdHelp').style.display = 'none';
        document.getElementById('userForm').classList.remove('was-validated');
    }
    
    function editUser(user) {
        document.getElementById('userModalLabel').textContent = 'Edit User Account';
        document.getElementById('formAction').value = 'edit';
        document.getElementById('userId').value = user.id;
        
        document.getElementById('fullNameField').value = user.full_name;
        document.getElementById('usernameField').value = user.username;
        document.getElementById('roleField').value = user.role;
        document.getElementById('emailField').value = user.email;
        document.getElementById('phoneField').value = user.phone || '';
        
        document.getElementById('passwordField').value = '';
        document.getElementById('passwordField').required = false;
        document.getElementById('pwdAsterisk').style.display = 'none';
        document.getElementById('pwdHelp').style.display = '';
        
        document.getElementById('userForm').classList.remove('was-validated');
        userModal.show();
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
