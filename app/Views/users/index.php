<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
User Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Compact Design Styles */
    .flex { display: flex; }
    .flex-col { flex-direction: column; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .space-y-2 > * + * { margin-top: 0.5rem; }
    .space-y-3 > * + * { margin-top: 0.75rem; }
    .space-x-2 > * + * { margin-left: 0.5rem; }
    .space-x-4 > * + * { margin-left: 1rem; }
    .bg-gradient-to-r { background: linear-gradient(to right, var(--tw-gradient-stops)); }
    .from-gray-900 { --tw-gradient-from: #111827; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(17, 24, 39, 0)); }
    .via-blue-900 { --tw-gradient-stops: var(--tw-gradient-from), #1e3a8a, var(--tw-gradient-to, rgba(30, 58, 138, 0)); }
    .to-purple-900 { --tw-gradient-to: #581c87; }
    .bg-clip-text { -webkit-background-clip: text; background-clip: text; }
    .text-transparent { color: transparent !important; }
    .tracking-tight { letter-spacing: -0.025em; }
    .text-gray-600 { color: #4b5563; }
    .text-gray-500 { color: #6b7280; }
    .text-gray-700 { color: #374151; }
    .text-gray-900 { color: #111827; }
    .text-gray-400 { color: #9ca3af; }
    .bg-green-500 { background-color: #10b981; }
    .bg-white { background-color: #ffffff; }
    .bg-gray-100 { background-color: #f3f4f6; }
    .bg-gray-200 { background-color: #e5e7eb; }
    .bg-blue-600 { background-color: #2563eb; }
    .bg-blue-700 { background-color: #1d4ed8; }
    .border-gray-100 { border-color: #f3f4f6; }
    .border-gray-200 { border-color: #e5e7eb; }
    .border-blue-500 { border-color: #3b82f6; }
    .rounded-lg { border-radius: 0.5rem; }
    .rounded-xl { border-radius: 0.75rem; }
    .rounded-2xl { border-radius: 1rem; }
    .rounded-full { border-radius: 9999px; }
    .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .w-2 { width: 0.5rem; }
    .h-2 { width: 0.5rem; }
    .w-4 { width: 1rem; }
    .h-4 { height: 1rem; }
    .w-full { width: 100%; }
    .max-w-2xl { max-width: 42rem; }
    .p-4 { padding: 1rem; }
    .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
    .px-4 { padding-left: 1rem; padding-right: 1rem; }
    .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .pr-10 { padding-right: 2.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .text-xs { font-size: 0.75rem; line-height: 1rem; }
    .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
    .text-base { font-size: 1rem; line-height: 1.5rem; }
    .text-2xl { font-size: 1.5rem; line-height: 2rem; }
    .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
    .font-medium { font-weight: 500; }
    .font-bold { font-weight: 700; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
    .gap-2 { gap: 0.5rem; }
    .gap-4 { gap: 1rem; }
    .block { display: block; }
    .relative { position: relative; }
    .absolute { position: absolute; }
    .inset-y-0 { top: 0; bottom: 0; }
    .right-0 { right: 0; }
    .pointer-events-none { pointer-events: none; }
    .appearance-none { appearance: none; }
    .border { border-width: 1px; }
    .focus\:outline-none:focus { outline: 2px solid transparent; outline-offset: 2px; }
    .focus\:ring-2:focus { --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color); --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color); box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000); }
    .focus\:ring-blue-500:focus { --tw-ring-opacity: 1; --tw-ring-color: rgba(59, 130, 246, var(--tw-ring-opacity)); }
    .focus\:border-blue-500:focus { border-color: #3b82f6; }
    .hover\:bg-blue-700:hover { background-color: #1d4ed8; }
    .hover\:bg-gray-200:hover { background-color: #e5e7eb; }
    .hover\:text-gray-600:hover { color: #4b5563; }
    .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
    .transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
    .duration-200 { transition-duration: 200ms; }
    .flex-1 { flex: 1 1 0%; }
    .text-center { text-align: center; }
    .text-white { color: #ffffff; }
    
    @keyframes pulse {
        50% { opacity: .5; }
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    
    @media (min-width: 640px) {
        .sm\:text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
    }

    @media (min-width: 1024px) {
        .lg\:flex-row { flex-direction: row; }
        .lg\:items-center { align-items: center; }
        .lg\:justify-between { justify-content: space-between; }
        .lg\:space-y-0 > * + * { margin-top: 0; }
        .lg\:text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
    }
    
    /* Additional Table Styles */
    .overflow-hidden { overflow: hidden; }
    .overflow-x-auto { overflow-x: auto; }
    .backdrop-blur-sm { backdrop-filter: blur(4px); }
    .bg-white\/50 { background-color: rgba(255, 255, 255, 0.5); }
    .bg-gray-50\/80 { background-color: rgba(249, 250, 251, 0.8); }
    .bg-blue-50\/50 { background-color: rgba(239, 246, 255, 0.5); }
    .bg-white\/70 { background-color: rgba(255, 255, 255, 0.7); }
    .border-gray-200\/50 { border-color: rgba(229, 231, 235, 0.5); }
    .divide-y > * + * { border-top-width: 1px; }
    .divide-gray-100\/50 > * + * { border-color: rgba(243, 244, 246, 0.5); }
    .whitespace-nowrap { white-space: nowrap; }
    .uppercase { text-transform: uppercase; }
    .w-8 { width: 2rem; }
    .h-8 { height: 2rem; }
    .w-12 { width: 3rem; }
    .h-12 { height: 3rem; }
    .w-3 { width: 0.75rem; }
    .h-3 { height: 0.75rem; }
    .mr-3 { margin-right: 0.75rem; }
    .mr-1 { margin-right: 0.25rem; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
    .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
    .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
    .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .font-semibold { font-weight: 600; }
    .bg-green-100 { background-color: #dcfce7; }
    .text-green-800 { color: #166534; }
    .bg-yellow-100 { background-color: #fef3c7; }
    .text-yellow-800 { color: #92400e; }
    .bg-gray-800 { background-color: #1f2937; }
    .text-gray-800 { color: #1f2937; }
    .inline-flex { display: inline-flex; }
    .items-center { align-items: center; }
    .justify-center { justify-content: center; }
    .space-y-3 > * + * { margin-top: 0.75rem; }
    .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
    .to-blue-600 { --tw-gradient-to: #2563eb; }
    .from-blue-600 { --tw-gradient-from: #2563eb; }
    .to-blue-700 { --tw-gradient-to: #1d4ed8; }
    .from-yellow-500 { --tw-gradient-from: #eab308; }
    .to-yellow-600 { --tw-gradient-to: #ca8a04; }
    .from-yellow-600 { --tw-gradient-from: #ca8a04; }
    .to-yellow-700 { --tw-gradient-to: #a16207; }
    .from-red-500 { --tw-gradient-from: #ef4444; }
    .to-red-600 { --tw-gradient-to: #dc2626; }
    .from-red-600 { --tw-gradient-from: #dc2626; }
    .to-red-700 { --tw-gradient-to: #b91c1c; }
    .hover\:from-blue-600:hover { --tw-gradient-from: #2563eb; }
    .hover\:to-blue-700:hover { --tw-gradient-to: #1d4ed8; }
    .hover\:from-yellow-600:hover { --tw-gradient-from: #ca8a04; }
    .hover\:to-yellow-700:hover { --tw-gradient-to: #a16207; }
    .hover\:from-red-600:hover { --tw-gradient-from: #dc2626; }
    .hover\:to-red-700:hover { --tw-gradient-to: #b91c1c; }
    .hover\:bg-white\/70:hover { background-color: rgba(255, 255, 255, 0.7); }
    .hover\:shadow-lg:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .hover\:scale-110:hover { transform: scale(1.1); }
    .hover\:-translate-y-1:hover { transform: translateY(-0.25rem); }
    .group:hover .group-hover\:rotate-12 { transform: rotate(12deg); }
    .transform { transform: translateX(var(--tw-translate-x)) translateY(var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y)); }
    .transition-transform { transition-property: transform; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
    .duration-300 { transition-duration: 300ms; }

    /* Glass card styling (matched to Role Management) */
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
</style>

    <!-- Header Section -->
    <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
            <div class="space-y-3">
                <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">User Management</h1>
                <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Monitor and manage system users efficiently</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-caption text-xs">Live Updates</span>
                    </div>
                    <div class="text-caption text-xs">Last Updated: <?= date('M d, Y \\a\\t g:i A') ?></div>
                </div>
            </div>
        </div>
    </div>


    <!-- Filters Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-6">
        <form action="<?= site_url('users') ?>" method="get" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Department Filter -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <div class="relative">
                    <select name="department" class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">All Departments</option>
                        <option value="Administration" <?= (isset($department) && $department == 'Administration') ? 'selected' : '' ?>>Administration</option>
                        <option value="IT" <?= (isset($department) && $department == 'IT') ? 'selected' : '' ?>>IT</option>
                        <option value="HR" <?= (isset($department) && $department == 'HR') ? 'selected' : '' ?>>HR</option>
                        <option value="Finance" <?= (isset($department) && $department == 'Finance') ? 'selected' : '' ?>>Finance</option>
                        <option value="Operations" <?= (isset($department) && $department == 'Operations') ? 'selected' : '' ?>>Operations</option>
                        <option value="Marketing" <?= (isset($department) && $department == 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <div class="relative">
                    <select name="status" class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">All Statuses</option>
                        <option value="active" <?= (isset($status) && $status == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="online" <?= (isset($status) && $status == 'online') ? 'selected' : '' ?>>Online</option>
                        <option value="inactive" <?= (isset($status) && $status == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        <option value="offline" <?= (isset($status) && $status == 'offline') ? 'selected' : '' ?>>Offline</option>
                        <option value="suspended" <?= (isset($status) && $status == 'suspended') ? 'selected' : '' ?>>Suspended</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i class="bi bi-search text-gray-400" id="mainSearchIcon"></i>
                        <div class="spinner-border spinner-border-sm text-primary d-none" id="mainSearchSpinner" role="status">
                            <span class="visually-hidden">Searching...</span>
                        </div>
                    </div>
                    <input type="text" name="q" placeholder="Search users..." class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-10 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" value="<?= esc($q ?? '') ?>">
                </div>
            </div>

            <!-- Items Per Page Filter -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Per Page</label>
                <div class="relative">
                    <select name="per_page" class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="25" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '25') ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= (!isset($_GET['per_page']) || $_GET['per_page'] == '50') ? 'selected' : '' ?>>50 (Default)</option>
                        <option value="75" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '75') ? 'selected' : '' ?>>75</option>
                        <option value="100" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '100') ? 'selected' : '' ?>>100</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </form>
    </div>

        <!-- Users Table Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden" style="max-height: none;">
            <div class="overflow-x-auto" style="max-height: none; overflow-y: visible;">
                <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                    <thead class="bg-gradient-to-r from-gray-50/80 to-blue-50/50 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Profile</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fake Reports</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">2FA</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-white/70 transition-all duration-200 backdrop-blur-sm">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if (!empty($user['profile_image'])): ?>
                                            <img src="<?= esc($user['profile_image']) ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover mr-3" 
                                                 referrerpolicy="no-referrer"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 text-white text-xs font-semibold" style="display:none;">
                                                <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 text-white text-xs font-semibold">
                                                <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($user['full_name'] ?? 'N/A') ?></div>
                                    <div class="text-sm text-gray-500">ID: <?= esc($user['id']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?= esc($user['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500"><?= esc($user['department'] ?? 'N/A') ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $status = $user['status'] ?? 'active';
                                    $suspendedUntil = $user['suspended_until'] ?? null;
                                    $suspensionReason = $user['suspension_reason'] ?? null;
                                    
                                    if ($status === 'active') {
                                        $statusClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                        $statusIcon = 'bi-check-circle-fill text-emerald-600';
                                        $statusText = 'Active';
                                        $statusDetail = '';
                                    } elseif ($status === 'Suspended' || $status === 'Temporary Suspended' || $status === 'Permanently Suspended') {
                                        $statusClass = 'bg-red-100 text-red-800 border-red-200';
                                        $statusIcon = 'bi-x-circle-fill text-red-500';
                                        
                                        if ($suspendedUntil) {
                                            // Temporary suspension
                                            $suspendedUntilDate = new DateTime($suspendedUntil);
                                            $now = new DateTime();
                                            $interval = $now->diff($suspendedUntilDate);
                                            
                                            if ($suspendedUntilDate > $now) {
                                                $days = $interval->days;
                                                $hours = $interval->h;
                                                $minutes = $interval->i;
                                                
                                                if ($days > 0) {
                                                    $timeRemaining = $days . 'd ' . $hours . 'h';
                                                } elseif ($hours > 0) {
                                                    $timeRemaining = $hours . 'h ' . $minutes . 'm';
                                                } else {
                                                    $timeRemaining = $minutes . 'm';
                                                }
                                                
                                                $statusText = 'Temporary Suspended';
                                                $statusDetail = '<div class="text-xs text-red-600 mt-1" data-suspended-until="' . $suspendedUntil . '">Expires in: ' . $timeRemaining . '</div>';
                                            } else {
                                                // Suspension expired, should be auto-unsuspended
                                                $statusText = 'Suspension Expired';
                                                $statusDetail = '<div class="text-xs text-orange-600 mt-1">Needs review</div>';
                                            }
                                        } else {
                                            // Permanent suspension
                                            $statusText = 'Permanently Suspended';
                                            $statusDetail = '<div class="text-xs text-red-600 mt-1">Permanent</div>';
                                        }
                                    } elseif ($status === 'inactive') {
                                        $statusClass = 'bg-gray-100 text-gray-800 border-gray-200';
                                        $statusIcon = 'bi-pause-circle-fill text-gray-500';
                                        $statusText = 'Inactive';
                                        $statusDetail = '';
                                    } elseif ($status === 'Offline' || $status === 'offline') {
                                        $statusClass = 'bg-slate-100 text-slate-800 border-slate-200';
                                        $statusIcon = 'bi-wifi-off text-slate-500';
                                        $statusText = 'Offline';
                                        $statusDetail = '';
                                    } elseif ($status === 'Online' || $status === 'online') {
                                        $statusClass = 'bg-green-100 text-green-800 border-green-200';
                                        $statusIcon = 'bi-wifi text-green-600';
                                        $statusText = 'Online';
                                        $statusDetail = '';
                                    } else {
                                        $statusClass = 'bg-blue-100 text-blue-800 border-blue-200';
                                        $statusIcon = 'bi-info-circle-fill text-blue-500';
                                        $statusText = ucfirst($status);
                                        $statusDetail = '';
                                    }
                                    ?>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                            <i class="bi <?= $statusIcon ?> mr-1"></i>
                                            <?= esc($statusText) ?>
                                        </span>
                                        <?= $statusDetail ?>
                                        <?php if (($status === 'Suspended' || $status === 'Temporary Suspended' || $status === 'Permanently Suspended') && $suspensionReason): ?>
                                            <div class="text-xs text-gray-500 mt-1" title="<?= esc($suspensionReason) ?>">
                                                Reason: <?= esc(strlen($suspensionReason) > 30 ? substr($suspensionReason, 0, 30) . '...' : $suspensionReason) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php 
                                    $fakeReportCount = $user['fake_report_count'] ?? 0;
                                    $reportClass = 'bg-gray-100 text-gray-800';
                                    $reportIcon = 'bi-shield-check';
                                    
                                    if ($fakeReportCount > 5) {
                                        $reportClass = 'bg-red-100 text-red-800';
                                        $reportIcon = 'bi-exclamation-triangle-fill';
                                    } elseif ($fakeReportCount > 2) {
                                        $reportClass = 'bg-yellow-100 text-yellow-800';
                                        $reportIcon = 'bi-exclamation-circle-fill';
                                    } elseif ($fakeReportCount > 0) {
                                        $reportClass = 'bg-orange-100 text-orange-800';
                                        $reportIcon = 'bi-exclamation-circle';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $reportClass ?>">
                                        <i class="bi <?= $reportIcon ?> mr-1"></i>
                                        <?= $fakeReportCount ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if (isset($user['two_factor_enabled']) && $user['two_factor_enabled'] === 'Enabled'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-shield-fill-check mr-1"></i>
                                            Enabled
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="bi bi-shield-fill-x mr-1"></i>
                                            Disabled
                                        </span>
                                    <?php endif; ?>
                                </
/span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="window.location.href='<?= site_url('users/view/' . $user['id']) ?>'" title="View Details">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-medium rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="window.location.href='<?= site_url('users/edit/' . $user['id']) ?>'" title="Edit User">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <?php if (isset($user['status']) && ($user['status'] === 'Suspended' || $user['status'] === 'Temporary Suspended' || $user['status'] === 'Permanently Suspended')): ?>
                                            <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-medium rounded-md hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="if(confirm('Are you sure you want to unsuspend this user?')) window.location.href='<?= site_url('users/unsuspend/' . $user['id']) ?>'" title="Unsuspend User">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-medium rounded-md hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="openSuspendModal(<?= $user['id'] ?>, '<?= esc($user['full_name']) ?>')" title="Suspend User">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636L5.636 18.364M5.636 5.636l12.728 12.728M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="if(confirm('Are you sure you want to delete this user?')) window.location.href='<?= site_url('users/delete/' . $user['id']) ?>'" title="Delete User">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="bi bi-people text-gray-400 text-xl"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">No users found</div>
                                    <div class="text-sm text-gray-500">Try adjusting your search or filter criteria</div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="bg-white px-6 py-3 border-t border-gray-200/50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <?= $pager->getFirstItem() ?> to <?= $pager->getLastItem() ?> of <?= $pager->getTotal() ?> results
                </div>
                <div class="pagination-wrapper">
                    <?= $pager->links('users', 'default_full') ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Debounce function for automatic search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Show/hide search loading indicators
    function showSearchLoading() {
        const searchIcon = document.getElementById('mainSearchIcon');
        const searchSpinner = document.getElementById('mainSearchSpinner');
        if (searchIcon && searchSpinner) {
            searchIcon.classList.add('d-none');
            searchSpinner.classList.remove('d-none');
        }
    }

    function hideSearchLoading() {
        const searchIcon = document.getElementById('mainSearchIcon');
        const searchSpinner = document.getElementById('mainSearchSpinner');
        if (searchIcon && searchSpinner) {
            searchIcon.classList.remove('d-none');
            searchSpinner.classList.add('d-none');
        }
    }

    // Debounced search functions
    const debouncedFormSubmit = debounce(() => {
        const form = document.querySelector('form[action*="users"]');
        if (form) {
            showSearchLoading();
            form.submit();
        }
    }, 500);

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchValue = this.value.trim();
                if (searchValue === '') {
                    hideSearchLoading();
                    window.location.href = '<?= site_url('users') ?>';
                } else {
                    showSearchLoading();
                    debouncedFormSubmit();
                }
            });
        }

        // Auto-filter on Department change
        const departmentSelect = document.querySelector('select[name="department"]');
        if (departmentSelect) {
            departmentSelect.addEventListener('change', function() {
                showSearchLoading();
                debouncedFormSubmit();
            });
        }

        // Auto-filter on Status change
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                showSearchLoading();
                debouncedFormSubmit();
            });
        }

        // Auto-filter on Per Page change
        const perPageSelect = document.querySelector('select[name="per_page"]');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                showSearchLoading();
                debouncedFormSubmit();
            });
        }
    });



    function clearFilters() {
        // Clear all filter inputs
        const form = document.querySelector('form[action*="users"]');
        if (form) {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type === 'text' || input.type === 'search') {
                    input.value = '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });
        }
        
        // Show all rows
        const rows = document.querySelectorAll('#usersTableBody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
        
        // Update URL to remove parameters
        window.location.href = '<?= site_url('users') ?>';
    }

    function openSuspendModal(userId, userName) {
        document.getElementById('suspendForm').action = '<?= site_url('users/suspend') ?>/' + userId;
        document.getElementById('suspendUserName').textContent = userName;
        document.getElementById('suspendModal').classList.remove('hidden');
    }

    function closeSuspendModal() {
        document.getElementById('suspendModal').classList.add('hidden');
        document.getElementById('suspendForm').reset();
    }

    function toggleSuspensionDays() {
        const suspensionType = document.querySelector('input[name="suspension_type"]:checked').value;
        const daysInput = document.getElementById('suspensionDaysGroup');
        
        if (suspensionType === 'temporary') {
            daysInput.classList.remove('hidden');
            document.getElementById('suspension_days').required = true;
        } else {
            daysInput.classList.add('hidden');
            document.getElementById('suspension_days').required = false;
        }
    }

    // Function to update suspension countdown in real-time
    function updateSuspensionCountdowns() {
        const suspensionElements = document.querySelectorAll('[data-suspended-until]');
        
        suspensionElements.forEach(function(element) {
            const suspendedUntil = element.getAttribute('data-suspended-until');
            if (!suspendedUntil) return;
            
            const suspendedUntilDate = new Date(suspendedUntil);
            const now = new Date();
            const timeDiff = suspendedUntilDate - now;
            
            if (timeDiff > 0) {
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                
                let timeRemaining;
                if (days > 0) {
                    timeRemaining = days + 'd ' + hours + 'h ' + minutes + 'm';
                } else if (hours > 0) {
                    timeRemaining = hours + 'h ' + minutes + 'm ' + seconds + 's';
                } else if (minutes > 0) {
                    timeRemaining = minutes + 'm ' + seconds + 's';
                } else {
                    timeRemaining = seconds + 's';
                }
                
                element.innerHTML = 'Expires in: ' + timeRemaining;
            } else {
                element.innerHTML = 'Expired - Needs review';
                element.className = 'text-xs text-orange-600 mt-1';
            }
        });
    }

    // Update countdowns every second
    setInterval(updateSuspensionCountdowns, 1000);
    
    // Initial update
    updateSuspensionCountdowns();
</script>

<!-- Suspension Modal -->
<div id="suspendModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Suspend User</h3>
                <button type="button" onclick="closeSuspendModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">
                You are about to suspend <strong id="suspendUserName"></strong>. Please specify the suspension details.
            </p>
            
            <form id="suspendForm" action="<?= site_url('users/suspend') ?>" method="POST">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Suspension Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="suspension_type" value="temporary" onchange="toggleSuspensionDays()" class="mr-2" checked>
                            <span class="text-sm">Temporary</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="suspension_type" value="permanent" onchange="toggleSuspensionDays()" class="mr-2">
                            <span class="text-sm">Permanent</span>
                        </label>
                    </div>
                </div>
                
                <div id="suspensionDaysGroup" class="mb-4">
                    <label for="suspension_days" class="block text-sm font-medium text-gray-700 mb-1">Number of Days</label>
                    <input type="number" id="suspension_days" name="suspension_days" min="1" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter number of days" required>
                </div>
                
                <div class="mb-4">
                    <label for="suspension_reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Suspension</label>
                    <textarea id="suspension_reason" name="suspension_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter reason for suspension" required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeSuspendModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        Suspend User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

