<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
User Details - <?= esc($user['full_name']) ?>
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    // Update time immediately and then every minute
    document.addEventListener('DOMContentLoaded', function() {
        updateTime();
        setInterval(updateTime, 60000);
    });
</script>

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
    .min-h-screen {
        min-height: 100vh;
    }
    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, #eef2ff, #ffffff, #ecfeff);
    }
    .from-indigo-50 {
        --tw-gradient-from: #eef2ff;
    }
    .via-white {
        --tw-gradient-via: #ffffff;
    }
    .to-cyan-50 {
        --tw-gradient-to: #ecfeff;
    }
    .p-4 { padding: 1rem; }
    .p-6 { padding: 1.5rem; }
    .p-8 { padding: 2rem; }
    .mb-8 { margin-bottom: 2rem; }
    .rounded-2xl { border-radius: 1rem; }
    .rounded-3xl { border-radius: 1.5rem; }
    .border { border-width: 1px; }
    .border-white\/20 { border-color: rgba(255, 255, 255, 0.2); }
    .flex { display: flex; }
    .flex-col { flex-direction: column; }
    .justify-between { justify-content: space-between; }
    .items-start { align-items: flex-start; }
    .items-center { align-items: center; }
    .gap-4 { gap: 1rem; }
    .space-y-2 > * + * { margin-top: 0.5rem; }
    .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
    .text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
    .font-bold { font-weight: 700; }
    .text-gray-900 { color: #111827; }
    .tracking-tight { letter-spacing: -0.025em; }
    .text-gray-600 { color: #4b5563; }
    .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
    .text-gray-500 { color: #6b7280; }
    .space-x-4 > * + * { margin-left: 1rem; }
    .w-4 { width: 1rem; }
    .h-4 { height: 1rem; }
    .mr-1 { margin-right: 0.25rem; }
    .w-5 { width: 1.25rem; }
    .h-5 { height: 1.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .max-w-6xl { max-width: 72rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .overflow-hidden { overflow: hidden; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    .from-indigo-600 { --tw-gradient-from: #4f46e5; }
    .via-purple-600 { --tw-gradient-via: #9333ea; }
    .to-pink-600 { --tw-gradient-to: #db2777; }
    .text-white { color: #ffffff; }
    .mb-1 { margin-bottom: 0.25rem; }
    .text-white\/80 { color: rgba(255, 255, 255, 0.8); }
    .text-white\/90 { color: rgba(255, 255, 255, 0.9); }
    .text-right { text-align: right; }
    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
    .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .lg\:col-span-2 { grid-column: span 2 / span 2; }
    .gap-8 { gap: 2rem; }
    .space-y-8 > * + * { margin-top: 2rem; }
    .bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
    .from-blue-50 { --tw-gradient-from: #eff6ff; }
    .to-indigo-50 { --tw-gradient-to: #eef2ff; }
    .border-blue-100 { border-color: #dbeafe; }
    .space-x-3 > * + * { margin-left: 0.75rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .p-2 { padding: 0.5rem; }
    .bg-blue-500 { background-color: #3b82f6; }
    .rounded-lg { border-radius: 0.5rem; }
    .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .text-gray-800 { color: #1f2937; }
    .relative { position: relative; }
    .group { /* group class for hover effects */ }
    .cursor-pointer { cursor: pointer; }
    .inline-flex { display: inline-flex; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .bg-white\/80 { background-color: rgba(255, 255, 255, 0.8); }
    .hover\:bg-white:hover { background-color: #ffffff; }
    .text-gray-700 { color: #374151; }
    .hover\:text-gray-900:hover { color: #111827; }
    .font-medium { font-weight: 500; }
    .rounded-xl { border-radius: 0.75rem; }
    .border-gray-200 { border-color: #e5e7eb; }
    .hover\:border-gray-300:hover { border-color: #d1d5db; }
    .transition-all { transition-property: all; }
    .duration-300 { transition-duration: 300ms; }
    .hover\:shadow-lg:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .hover\:-translate-y-0\.5:hover { transform: translateY(-0.125rem); }
    .w-8 { width: 2rem; }
    .h-8 { height: 2rem; }
    .bg-white\/20 { background-color: rgba(255, 255, 255, 0.2); }
    .text-2xl { font-size: 1.5rem; line-height: 2rem; }
    .flex-shrink-0 { flex-shrink: 0; }
    .bg-white\/10 { background-color: rgba(255, 255, 255, 0.1); }
    .p-3 { padding: 0.75rem; }
    .backdrop-filter { backdrop-filter: var(--tw-backdrop-filter); }
    .backdrop-blur-10 { --tw-backdrop-filter: blur(10px); }
    .border-white\/30 { border-color: rgba(255, 255, 255, 0.3); }
    .text-white\/70 { color: rgba(255, 255, 255, 0.7); }
    .text-xs { font-size: 0.75rem; line-height: 1rem; }
    .uppercase { text-transform: uppercase; }
    .tracking-wide { letter-spacing: 0.025em; }
    .mb-2 { margin-bottom: 0.5rem; }
    .font-semibold { font-weight: 600; }
    .px-4 { padding-left: 1rem; padding-right: 1rem; }
    .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .rounded-full { border-radius: 9999px; }
    .bg-green-500\/20 { background-color: rgba(34, 197, 94, 0.2); }
    .text-green-400 { color: #4ade80; }
    .border-green-500\/30 { border-color: rgba(34, 197, 94, 0.3); }
    .bg-gray-500\/20 { background-color: rgba(107, 114, 128, 0.2); }
    .text-gray-400 { color: #9ca3af; }
    .border-gray-500\/30 { border-color: rgba(107, 114, 128, 0.3); }
    .gap-3 { gap: 0.75rem; }
    .flex-wrap { flex-wrap: wrap; }
    .px-5 { padding-left: 1.25rem; padding-right: 1.25rem; }
    .bg-yellow-500\/20 { background-color: rgba(234, 179, 8, 0.2); }
    .text-yellow-400 { color: #facc15; }
    .border-yellow-500\/30 { border-color: rgba(234, 179, 8, 0.3); }
    .hover\:bg-yellow-500\/30:hover { background-color: rgba(234, 179, 8, 0.3); }
    .hover\:-translate-y-1:hover { transform: translateY(-0.25rem); }
    .bg-red-500\/20 { background-color: rgba(239, 68, 68, 0.2); }
    .text-red-400 { color: #f87171; }
    .border-red-500\/30 { border-color: rgba(239, 68, 68, 0.3); }
    .hover\:bg-red-500\/30:hover { background-color: rgba(239, 68, 68, 0.3); }
    @media (min-width: 640px) {
        .sm\:p-6 { padding: 1.5rem; }
        .sm\:flex-row { flex-direction: row; }
        .sm\:items-center { align-items: center; }
        .sm\:text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
    }
    @media (min-width: 1024px) {
        .lg\:p-8 { padding: 2rem; }
        .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .lg\:col-span-2 { grid-column: span 2 / span 2; }
    }

    /* Match Management Quick Actions style */
    .action-button {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 0.875rem 1.5rem;
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
    .action-button:hover::before {
        left: 100%;
    }
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* Animated icons */
    .animated-icon { transition: transform 0.3s ease; }
    .action-button:hover .animated-icon { transform: rotate(10deg) scale(1.1); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 p-4 sm:p-6 lg:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Card -->
        <div class="glass-card rounded-3xl p-0 mb-8 overflow-hidden shadow-2xl border border-white/20">
            <div class="bg-blue-600 h-1 w-full"></div>
            <div class="p-6 sm:p-6 lg:p-8 rounded-2xl bg-white">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="space-y-2">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-1">
                            User Profile
                        </h1>
                        <p class="text-gray-600 text-lg">
                            Detailed information and account management
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-700 text-sm space-x-4">
                            <span class="inline-flex items-center">
                                <i class="bi bi-circle-fill w-4 h-4 mr-1 text-green-500"></i>
                                Live Updates
                            </span>
                            <span class="inline-flex items-center">
                                <i class="bi bi-clock w-5 h-5 mr-2 text-gray-700"></i>
                                <span id="current-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Details Section -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Profile Card -->
                <div class="glass-card rounded-2xl p-6 border border-white/20 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <i class="bi bi-person text-white w-5 h-5"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Profile Information</h2>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="relative group cursor-pointer">
                            <?php if (!empty($user['profile_image'])): ?>
                            <img src="<?= esc($user['profile_image']) ?>" alt="Profile Picture" class="w-20 h-20 rounded-2xl object-cover flex-shrink-0" referrerpolicy="no-referrer"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0 text-white text-xl font-bold" style="display:none;">
                                <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 2)) ?>
                            </div>
                            <?php else: ?>
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0 text-white text-xl font-bold">
                                <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 2)) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">
                                <?= esc($user['full_name']) ?>
                            </h3>
                            <p class="text-gray-600 text-lg mb-2"><?= esc($user['email']) ?></p>
                            <div class="flex items-center gap-3 flex-wrap">
                                <?php 
                                $status = $user['status'] ?? 'Online';
                                $statusClass = $status === 'Online' ? 'bg-green-500/20 text-green-400 border-green-500/30' : ($status === 'Offline' ? 'bg-red-500/20 text-red-400 border-red-500/30' : 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30');
                                $statusIcon = $status === 'Online' ? 'bi-wifi text-green-500' : ($status === 'Offline' ? 'bi-wifi-off text-red-400' : 'bi-exclamation-triangle-fill text-yellow-400');
                                $statusText = $status === 'Online' ? 'Online' : ($status === 'Offline' ? 'Offline' : ucfirst($status));
                                ?>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border <?= $statusClass ?>">
                                    <i class="bi <?= $statusIcon ?> w-4 h-4 mr-1"></i>
                                    <?= esc($statusText) ?>
                                </span>
                                <?php if (isset($user['two_factor_enabled']) && $user['two_factor_enabled'] === 'Enabled'): ?>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                        <i class="bi bi-shield-fill-check w-4 h-4 mr-1"></i>
                                        2FA Enabled
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                        <i class="bi bi-shield-fill-x w-4 h-4 mr-1"></i>
                                        2FA Disabled
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="glass-card rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <i class="bi bi-info-circle text-white w-5 h-5"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Account Details</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                            <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-2">Department</h6>
                            <div class="text-gray-900 font-semibold"><?= esc($user['department']) ?></div>
                        </div>
                        <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                            <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-2">Member Since</h6>
                            <div class="text-gray-900 font-semibold"><?= isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A' ?></div>
                        </div>
                        <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                            <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-2">Fake Reports Count</h6>
                            <?php 
                            $fakeReportCount = $user['fake_report_count'] ?? 0;
                            $reportClass = 'text-gray-900';
                            $reportBadgeClass = 'bg-gray-100 text-gray-800';
                            
                            if ($fakeReportCount > 5) {
                                $reportClass = 'text-red-600';
                                $reportBadgeClass = 'bg-red-100 text-red-800';
                            } elseif ($fakeReportCount > 2) {
                                $reportClass = 'text-yellow-600';
                                $reportBadgeClass = 'bg-yellow-100 text-yellow-800';
                            } elseif ($fakeReportCount > 0) {
                                $reportClass = 'text-orange-600';
                                $reportBadgeClass = 'bg-orange-100 text-orange-800';
                            }
                            ?>
                            <div class="flex items-center gap-2">
                                <span class="<?= $reportClass ?> font-semibold text-lg"><?= $fakeReportCount ?></span>
                                <?php if ($fakeReportCount > 5): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $reportBadgeClass ?>">
                                        <i class="bi bi-exclamation-triangle-fill mr-1"></i>
                                        High Risk
                                    </span>
                                <?php elseif ($fakeReportCount > 2): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $reportBadgeClass ?>">
                                        <i class="bi bi-exclamation-circle-fill mr-1"></i>
                                        Warning
                                    </span>
                                <?php elseif ($fakeReportCount > 0): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $reportBadgeClass ?>">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        Caution
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (isset($user['last_login']) && $user['last_login']): ?>
                        <div class="bg-white/10 p-3 rounded-xl backdrop-filter backdrop-blur-10 border border-white/30">
                            <h6 class="text-gray-500 text-xs uppercase tracking-wide mb-2">Last Login</h6>
                            <div class="text-gray-900 font-semibold"><?= date('M d, Y g:i A', strtotime($user['last_login'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="space-y-6">
                <div class="glass-card rounded-2xl p-6 border border-white/20">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?= site_url('users/edit/' . $user['id']) ?>" 
                           class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                            <i class="bi bi-pencil animated-icon w-5 h-5 mr-2"></i>
                            Edit User
                        </a>
                        <a href="<?= site_url('users/delete/' . $user['id']) ?>" onclick="return confirm('Are you sure you want to delete this user?')" 
                           class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                            <i class="bi bi-trash animated-icon w-5 h-5 mr-2"></i>
                            Delete User
                        </a>
                        <a href="<?= site_url('users') ?>" 
                           class="relative group cursor-pointer inline-flex items-center justify-center px-5 py-3 bg-white/80 hover:bg-white text-gray-700 hover:text-gray-900 font-medium rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 w-full">
                            <i class="bi bi-arrow-left w-5 h-5 mr-2"></i>
                            Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
