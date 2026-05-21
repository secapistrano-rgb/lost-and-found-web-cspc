<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Role Details - <?= esc($role['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    .profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        background-color: #e9ecef;
        flex-shrink: 0;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Match Management Quick Actions style */
    .action-button {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    .action-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .action-button:hover::before { left: 100%; }
    .action-button:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }

    /* Animated icons */
    .animated-icon { transition: transform 0.3s ease; }
    .action-button:hover .animated-icon { transform: rotate(10deg) scale(1.1); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    <!-- Header Section -->
    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl border border-white/20 mb-6">
        <div class="h-1 bg-blue-600"></div>
        <div class="p-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight mb-1">
                        Role Details
                    </h1>
                    <p class="text-gray-600 text-sm">
                        Detailed role information and management
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

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <!-- Profile Information -->
        <div class="glass-card rounded-2xl p-4 border border-white/20 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
            <div class="flex items-center space-x-3 mb-4">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <i class="bi bi-person text-white w-4 h-4"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Profile Information</h2>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="profile-avatar">
                    <?php
                        $imageUrl = !empty($role['profile_pic']) 
                            ? $role['profile_pic'] 
                            : get_gravatar($role['email']);
                    ?>
                    <img src="<?= $imageUrl ?>" alt="<?= esc($role['name']) ?>" referrerpolicy="no-referrer">
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        <?= esc($role['name']) ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-2"><?= esc($role['email']) ?></p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-600 border border-blue-500/30">
                        <i class="bi bi-shield-fill w-3 h-3 mr-1"></i>
                        <?= esc(ucwords($role['role'])) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="glass-card rounded-2xl p-4 border border-white/20">
            <div class="flex items-center space-x-3 mb-4">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <i class="bi bi-info-circle text-white w-4 h-4"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Account Details</h2>
            </div>
            
            <div class="space-y-3">
                <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                    <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-1">Name</h6>
                    <div class="text-gray-900 font-semibold text-sm"><?= esc($role['name']) ?></div>
                </div>
                <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                    <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-1">Email</h6>
                    <div class="text-gray-900 font-semibold text-sm"><?= esc($role['email']) ?></div>
                </div>
                <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                    <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-1">Role</h6>
                    <div class="text-gray-900 font-semibold text-sm"><?= esc(ucwords($role['role'])) ?></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card rounded-2xl p-4 border border-white/20">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="<?= site_url('admin_role/edit/' . $role['id']) ?>" class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                    <i class="bi bi-pencil animated-icon w-5 h-5 mr-2"></i>
                    Edit Role
                </a>
                <a href="<?= site_url('admin_role/delete/' . $role['id']) ?>" onclick="return confirm('Are you sure you want to delete this role?')" class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                    <i class="bi bi-trash animated-icon w-5 h-5 mr-2"></i>
                    Delete Role
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
