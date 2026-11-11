<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Role Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mg-page mg-roles">
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

    /* Action buttons with gradients */
    .btn-action-view {
        background: linear-gradient(135deg, #00B2D4, #0891b2);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-action-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 178, 212, 0.4);
        color: white;
    }
    
    .btn-action-edit {
        background: linear-gradient(135deg, #FFC107, #f59e0b);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-action-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        color: white;
    }
    
    .btn-action-delete {
        background: linear-gradient(135deg, #E53935, #dc2626);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-action-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(229, 57, 53, 0.4);
        color: white;
    }
    
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        margin: 0;
    }
    
    /* Gradient text utilities to match User Management header */
    .bg-gradient-to-r { background: linear-gradient(to right, var(--tw-gradient-stops)); }
    .from-gray-900 { --tw-gradient-from: #111827; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(17, 24, 39, 0)); }
    .via-blue-900 { --tw-gradient-stops: var(--tw-gradient-from), #1e3a8a, var(--tw-gradient-to, rgba(30, 58, 138, 0)); }
    .to-purple-900 { --tw-gradient-to: #581c87; }
    .bg-clip-text { -webkit-background-clip: text; background-clip: text; }
    .text-transparent { color: transparent !important; }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <!-- Header Section -->
    <div class="mg-page-header glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6 overflow-hidden">
        <!-- <div class="h-1 bg-blue-600"></div> -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
            <div class="space-y-3">
                <p class="mg-eyebrow">ADMINISTRATION</p>
                    <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Role Management</h1>
                <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Manage the people responsible for your lost and found workspace.</p>
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

    <?= view('components/management_summary', ['summaryCards' => [
    ['label' => 'Accounts on this page', 'value' => count($roles ?? []), 'icon' => 'bi-people', 'tone' => 'blue'],
    ['label' => 'Super admins', 'value' => count(array_filter($roles ?? [], static fn($row) => ($row['role'] ?? '') === 'super_admin')), 'icon' => 'bi-shield-lock', 'tone' => 'slate'],
    ['label' => 'Administrators', 'value' => count(array_filter($roles ?? [], static fn($row) => ($row['role'] ?? '') === 'admin')), 'icon' => 'bi-person-gear', 'tone' => 'green'],
    ['label' => 'Staff members', 'value' => count(array_filter($roles ?? [], static fn($row) => ($row['role'] ?? '') === 'staff')), 'icon' => 'bi-person-badge', 'tone' => 'amber']
]]) ?>

<!-- Search, Filter & Actions Section -->
    <div class="mg-filter-panel bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-6">
            <!-- Search Bar -->
            <div class="flex-1 lg:max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg id="searchIcon" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <div id="searchSpinner" class="h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin hidden"></div>
                    </div>
                    <form action="<?= site_url('admin_role') ?>" method="get" class="w-full">
                        <input type="text" name="q" id="headerSearch" placeholder="Search roles, names, or emails..." value="<?= esc($q ?? '') ?>" class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </form>
                </div>
            </div>
            
            <!-- Role Filter -->
            <div class="flex-shrink-0">
                <select name="role_filter" id="roleFilter" class="px-4 py-3 bg-white/80 backdrop-blur-sm border border-white/30 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200 shadow-sm">
                    <option value="">All Roles</option>
                    <option value="admin" <?= (isset($role_filter) && $role_filter === 'admin') ? 'selected' : '' ?>>Admin</option>
                    <?php if (isset($currentUserRole) && $currentUserRole === 'super_admin'): ?>
                        <option value="super_admin" <?= (isset($role_filter) && $role_filter === 'super_admin') ? 'selected' : '' ?>>Super Admin</option>
                    <?php endif; ?>
                    <option value="staff" <?= (isset($role_filter) && $role_filter === 'staff') ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>
            
            <!-- Add New Role Button -->
            <div class="flex-shrink-0">
                <a href="<?= site_url('admin_role/create') ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Role
                </a>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="glass-card rounded-2xl overflow-hidden" style="max-height: none;">
        <div class="overflow-x-auto" style="max-height: none; overflow-y: visible;">
            <div class="mg-table-container"><table class="mg-table w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="bi bi-person-badge mr-2"></i>Admin
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="bi bi-shield-check mr-2"></i>Role
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="bi bi-gear mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white/80 divide-y divide-gray-100">
                    <?php if (!empty($roles)):
                        foreach ($roles as $role):
                    ?>
                            <tr class="hover:bg-white/70 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <?php
                                            $imageUrl = !empty($role['profile_pic']) 
                                                ? $role['profile_pic'] 
                                                : get_gravatar($role['email']);
                                        ?>
                                        <div class="relative w-12 h-12">
                                            <img src="<?= $imageUrl ?>" 
                                                 class="w-12 h-12 rounded-full border-2 border-white shadow-sm object-cover" 
                                                 alt="<?= esc($role['name']) ?>" 
                                                 referrerpolicy="no-referrer"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                 onload="this.style.display='block'; this.nextElementSibling.style.display='none';">
                                            <div class="w-12 h-12 rounded-full border-2 border-white shadow-sm bg-gray-300 flex items-center justify-center text-gray-600 font-semibold text-sm" 
                                                 style="display: none;">
                                                <?= strtoupper(substr($role['name'], 0, 2)) ?>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900"><?= esc($role['name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= esc($role['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800">
                                        <?= esc(ucwords(str_replace('_', ' ', $role['role']))) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-1">
                                        <a href="<?= site_url('admin_role/view/' . $role['id']) ?>" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="View">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="square" stroke-linejoin="square" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="square" stroke-linejoin="square" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="<?= site_url('admin_role/edit/' . $role['id']) ?>" class="inline-flex items-center justify-center px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-medium rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-105 hover:-translate-y-1 leading-none group" title="Edit">
                                            <i class="bi bi-pencil-square w-3 h-3 block transition-transform duration-300 group-hover:rotate-12"></i>
                                        </a>
                                        <a href="<?= site_url('admin_role/delete/' . $role['id']) ?>" onclick="return confirm('Are you sure you want to delete this role?');" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="Delete">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-3">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No roles found</p>
                                    <p class="text-sm">Get started by adding your first admin role.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table></div>
            
            <!-- Pagination -->
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing <?= $pager->getFirstItem() ?> to <?= $pager->getLastItem() ?> of <?= $pager->getTotal() ?> roles
                    </div>
                    <div>
                        <?= $pager->links('roles', 'default_full') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Show/hide search loading indicator
    function showSearchLoading() {
        const searchIcon = document.getElementById('searchIcon');
        const searchSpinner = document.getElementById('searchSpinner');
        
        if (searchIcon && searchSpinner) {
            searchIcon.classList.add('hidden');
            searchSpinner.classList.remove('hidden');
        }
    }

    function hideSearchLoading() {
        const searchIcon = document.getElementById('searchIcon');
        const searchSpinner = document.getElementById('searchSpinner');
        
        if (searchIcon && searchSpinner) {
            searchIcon.classList.remove('hidden');
            searchSpinner.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="q"]');
        const roleFilter = document.querySelector('#roleFilter');
        let searchTimeout;
        
        function performSearch() {
            showSearchLoading();
            
            const searchValue = searchInput.value.trim();
            const roleValue = roleFilter ? roleFilter.value : '';
            
            // Build URL with search parameters
            let url = '<?= site_url('admin_role') ?>';
            const params = new URLSearchParams();
            
            if (searchValue) {
                params.append('q', searchValue);
            }
            if (roleValue) {
                params.append('role_filter', roleValue);
            }
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            window.location.href = url;
        }
        
        if (searchInput) {
            // Automatic search with debounce (300ms delay)
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                if (this.value === '') {
                    // Immediate redirect when search is cleared
                    window.location.href = '<?= site_url('admin_role') ?>';
                } else {
                    // Debounced search for non-empty values
                    searchTimeout = setTimeout(performSearch, 300);
                }
            });
            
            // Also trigger search on Enter key for immediate response
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    performSearch();
                }
            });
        }
        
        // Auto-search when role filter changes
        if (roleFilter) {
            roleFilter.addEventListener('change', function() {
                clearTimeout(searchTimeout);
                performSearch();
            });
        }
    });
</script>
<?= $this->endSection() ?>
