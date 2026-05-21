<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Add New Role
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Glass card styling */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Form field styling */
    .form-field-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        margin-right: 8px;
    }

    .form-input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background-color: #f9fafb;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        ring: 2px;
        ring-color: #3b82f6;
        border-color: #3b82f6;
        background-color: white;
    }

    .password-toggle {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #374151;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        color: white;
    }

    .btn-secondary-outline {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #374151;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary-outline:hover {
        background: white;
        color: #1f2937;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <!-- Header Section -->
    <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6 overflow-hidden">
        <div class="h-1 bg-blue-600"></div>
        <div class="p-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                        <i class="bi bi-person-plus-fill mr-3"></i>Add New Role
                    </h1>
                    <p class="text-gray-600 text-sm">
                        Create a new admin or staff role with secure credentials
                    </p>
                </div>
                <div class="text-right">
                    <a href="<?= site_url('admin_role') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300">
                        <i class="bi bi-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="glass-card rounded-2xl p-6 border border-white/20 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
        <div class="flex items-center space-x-3 mb-6">
            <div class="p-2 bg-blue-500 rounded-lg">
                <i class="bi bi-person-fill-add text-white w-5 h-5"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Role Information</h2>
        </div>

        <form action="<?= site_url('admin_role/store') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            
            <!-- Full Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    <div class="form-field-icon bg-blue-100">
                        <i class="bi bi-person text-blue-600 w-4 h-4"></i>
                    </div>
                    <input type="text" id="name" name="name" required 
                           class="form-input" 
                           placeholder="Enter full name"
                           value="<?= old('name') ?>">
                </div>
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    <div class="form-field-icon bg-green-100">
                        <i class="bi bi-envelope text-green-600 w-4 h-4"></i>
                    </div>
                    <input type="email" id="email" name="email" required 
                           class="form-input" 
                           placeholder="Enter email address"
                           value="<?= old('email') ?>">
                </div>
                <p class="text-xs text-gray-500 mt-1">This will be used for login and notifications</p>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    <div class="form-field-icon bg-purple-100">
                        <i class="bi bi-lock text-purple-600 w-4 h-4"></i>
                    </div>
                    <div class="flex-1 relative">
                        <input type="password" id="password" name="password" required minlength="8" 
                               class="form-input pr-10" 
                               placeholder="Enter secure password">
                        <button type="button" id="togglePassword" class="password-toggle">
                            <i class="bi bi-eye w-4 h-4" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long with a mix of letters and numbers</p>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    <div class="form-field-icon bg-purple-100">
                        <i class="bi bi-lock-fill text-purple-600 w-4 h-4"></i>
                    </div>
                    <div class="flex-1 relative">
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8" 
                               class="form-input pr-10" 
                               placeholder="Confirm your password">
                        <button type="button" id="toggleConfirmPassword" class="password-toggle">
                            <i class="bi bi-eye w-4 h-4" id="toggleConfirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Please re-enter your password to confirm</p>
            </div>

            <!-- Role Field -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Role Assignment <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    <div class="form-field-icon bg-orange-100">
                        <i class="bi bi-person-badge text-orange-600 w-4 h-4"></i>
                    </div>
                    <select id="role" name="role" required class="form-input">
                        <option value="">Select a role...</option>
                        <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin - Full system access</option>
                        <?php if (isset($currentUserRole) && $currentUserRole === 'super_admin'): ?>
                            <option value="super_admin" <?= old('role') == 'super_admin' ? 'selected' : '' ?>>Super Admin - System administrator</option>
                        <?php endif; ?>
                        <option value="staff" <?= old('role') == 'staff' ? 'selected' : '' ?>>Staff - Limited access</option>
                    </select>
                </div>
                <p class="text-xs text-gray-500 mt-1">Choose the appropriate access level for this user</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-primary-gradient inline-flex items-center justify-center">
                    <i class="bi bi-check-circle w-4 h-4 mr-2"></i>
                    Create Role
                </button>
                <a href="<?= site_url('admin_role') ?>" class="btn-secondary-outline inline-flex items-center justify-center">
                    <i class="bi bi-x-circle w-4 h-4 mr-2"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
});

// Toggle confirm password visibility
document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const confirmPasswordField = document.getElementById('confirm_password');
    const toggleIcon = document.getElementById('toggleConfirmPasswordIcon');
    
    if (confirmPasswordField.type === 'password') {
        confirmPasswordField.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        confirmPasswordField.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
});

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value) {
        confirmPassword.dispatchEvent(new Event('input'));
    }
});

// Form validation feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match. Please check and try again.');
        return false;
    }
});
</script>

<?= $this->endSection() ?>