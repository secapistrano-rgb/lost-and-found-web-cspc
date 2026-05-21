<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Role
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl border border-white/20 mb-6">
        <div class="h-1 bg-blue-600"></div>
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                        Edit Role
                    </h1>
                    <p class="text-gray-600 text-sm">
                        Update role information and permissions
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-gray-700 text-xs space-x-3">
                        <span class="inline-flex items-center">
                            <i class="bi bi-circle-fill w-3 h-3 mr-1 text-green-500"></i>
                            Live Updates
                        </span>
                        <span class="inline-flex items-center">
                            <i class="bi bi-clock w-4 h-4 mr-1"></i>
                            <span id="current-time">09:33 PM</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="glass-card rounded-2xl p-4 border border-white/20 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
        <div class="flex items-center space-x-2 mb-4">
            <div class="p-1.5 bg-blue-500 rounded-lg">
                <i class="bi bi-pencil-square text-white w-4 h-4"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Role Information</h2>
        </div>
        <form action="<?= site_url('admin_role/update/' . $role['id']) ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            
            <!-- Full Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                        <i class="bi bi-person text-blue-600 w-4 h-4"></i>
                    </div>
                    <input type="text" id="name" name="name" value="<?= esc($role['name']) ?>" required class="flex-1 px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300 text-sm">
                </div>
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                        <i class="bi bi-envelope text-green-600 w-4 h-4"></i>
                    </div>
                    <input type="email" id="email" name="email" value="<?= esc($role['email']) ?>" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed text-sm">
                </div>
            </div>
            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    New Password <span class="text-xs text-gray-500">(Leave blank to keep current password)</span>
                </label>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-lg">
                        <i class="bi bi-lock text-purple-600 w-4 h-4"></i>
                    </div>
                    <div class="relative flex-1">
                        <input type="password" id="password" name="password" placeholder="Enter new password (optional)" class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-300 text-sm">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <i class="bi bi-eye w-4 h-4" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-10">Must be at least 8 characters with uppercase, lowercase, number, and special character</p>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm New Password
                </label>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-lg">
                        <i class="bi bi-lock-fill text-purple-600 w-4 h-4"></i>
                    </div>
                    <div class="relative flex-1">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter new password (optional)" class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-300 text-sm">
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <i class="bi bi-eye w-4 h-4" id="toggleConfirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Role Field -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                    Role
                </label>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-lg">
                        <i class="bi bi-shield text-yellow-600 w-4 h-4"></i>
                    </div>
                    <select id="role" name="role" class="flex-1 px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-300 text-sm" <?= ($role['role'] === 'super_admin') ? 'disabled' : '' ?> >
                        <option value="admin" <?= ($role['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                        <?php if (isset($currentUserRole) && $currentUserRole === 'super_admin'): ?>
                        <option value="super_admin" <?= ($role['role'] === 'super_admin') ? 'selected' : '' ?>>Super Admin</option>
                        <?php endif; ?>
                        <option value="staff" <?= ($role['role'] === 'staff') ? 'selected' : '' ?>>Staff</option>
                    </select>
                    <?php if ($role['role'] === 'super_admin'): ?>
                        <input type="hidden" name="role" value="super_admin">
                        <p class="text-xs text-gray-500 mt-1">Super Admin role cannot be changed.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="<?= site_url('admin_role') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-xl border border-gray-200 hover:bg-gray-200 transition-all duration-200">
                    <i class="bi bi-arrow-left w-4 h-4 mr-2"></i>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl border border-blue-700 transition-all duration-200">
                    <i class="bi bi-save w-4 h-4 mr-2"></i>
                    Save Changes
                </button>
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
    
    // Only validate if both fields have values
    if (password && confirmPassword) {
        if (password !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
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
</script>

<?= $this->endSection() ?>
