<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
CSPC Lost & Found - Item Management
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>


    /* Custom Tailwind-inspired styles */
    .glass-card {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .text-display {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 700;
        letter-spacing: -0.025em;
    }
    
    .text-subheading {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 500;
    }
    
    .text-caption {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 400;
    }
    
    .item-card {
        transition: all 0.2s ease;
    }
    
    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .item-actions {
        display: flex;
        gap: 0.375rem; /* slightly tighter spacing */
        justify-content: center;
    }
    
    .action-btn {
        border-radius: 9999px;
        padding: 0.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border: 2px solid transparent;
    }
    
    .action-btn:hover {
        transform: translateY(-3px) scale(1.15) rotate(3deg);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
        filter: brightness(1.1) contrast(1.05);
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 50%, rgba(0,0,0,0.05) 100%);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .action-btn:hover::before {
        opacity: 1;
    }
    
    .action-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 150%;
        height: 150%;
        background: radial-gradient(circle, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        transform: scale(0) translate(-50%, -50%);
        transform-origin: top left;
        opacity: 0;
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s;
        z-index: -1;
    }
    
    .action-btn:hover::after {
        transform: scale(2.5) translate(-50%, -50%);
        opacity: 1;
    }
    
    .action-btn:active {
        transform: translateY(0) scale(0.95);
        transition: all 0.1s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) inset;
    }
    
    /* Specific styles for each action button type */
    .action-btn.view-btn:hover {
        border-color: rgba(79, 70, 229, 0.3);
    }
    
    .action-btn.edit-btn:hover {
        border-color: rgba(202, 138, 4, 0.3);
    }
    
    .action-btn.delete-btn:hover {
        border-color: rgba(220, 38, 38, 0.3);
    }
    
    .item-badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }
    
    /* Table responsive styles */
    @media (max-width: 1280px) {
        .action-btn {
            padding: 0.125rem;
        }
    }
    
    @media (max-width: 1024px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
    
    @media (max-width: 768px) {
        .item-actions {
            flex-direction: column;
            gap: 0.25rem;
        }
    }

    /* Action buttons styled via Tailwind utility classes to match Match Management exactly. Removed previous custom .btn.btn-action-* styles to avoid conflicts. */
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
                <div class="space-y-3">
                    <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Item Management</h1>
                    <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Manage and track all lost and found items</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-caption text-xs">Live Updates</span>
                        </div>
                        <div class="text-caption text-xs">Last Updated: <?= date('M d, Y \\a\\t g:i A') ?></div>
                    </div>
                </div>
                
                <!-- Search moved to Filters; header search removed -->
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-lg mb-6">
            <div class="p-6">
                <form action="<?= site_url('items') ?>" method="get" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-white/30 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="active" <?= (($selectedStatus ?? '') == 'active') ? 'selected' : '' ?>>Pending Cases</option>
                                <option value="inactive" <?= (($selectedStatus ?? '') == 'inactive') ? 'selected' : '' ?>>Archived</option>
                                <option value="claimed" <?= (($selectedStatus ?? '') == 'claimed') ? 'selected' : '' ?>>Case Closed</option>
                                <option value="returned" <?= (($selectedStatus ?? '') == 'returned') ? 'selected' : '' ?>>Successfully Returned</option>
                                <option value="Pending Turning-in" <?= (($selectedStatus ?? '') == 'Pending Turning-in') ? 'selected' : '' ?>>Pending Turning-in</option>
                                <option value="Fake Report" <?= (($selectedStatus ?? '') == 'Fake Report') ? 'selected' : '' ?>>Fake Report</option>
                            </select>
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                            <select name="type" id="type" class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-white/30 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="">All Types</option>
                                <option value="lost" <?= (($selectedType ?? '') == 'lost') ? 'selected' : '' ?>>Lost</option>
                                <option value="found" <?= (($selectedType ?? '') == 'found') ? 'selected' : '' ?>>Found</option>
                            </select>
                        </div>
                        <!-- Search Bar -->
                        <div>
                            <label for="headerSearch" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i id="searchIcon" class="bi bi-search text-gray-400"></i>
                                    <div id="searchSpinner" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                        <span class="visually-hidden">Searching...</span>
                                    </div>
                                </div>
                                <input type="text" id="headerSearch" name="search" placeholder="Search..." class="search-input w-full pl-10 pr-10 py-3 bg-white/80 backdrop-blur-sm border border-white/30 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" value="<?= esc($search ?? '') ?>">
                            </div>
                        </div>
                        <!-- Per Page Filter -->
                        <div>
                            <label for="per_page" class="block text-sm font-semibold text-gray-700 mb-2">Per Page</label>
                            <select name="per_page" id="per_page" class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-white/30 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="25" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '25') ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= (!isset($_GET['per_page']) || $_GET['per_page'] == '50') ? 'selected' : '' ?>>50 (Default)</option>
                                <option value="75" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '75') ? 'selected' : '' ?>>75</option>
                                <option value="100" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '100') ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Table Section -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-6 border-b border-white/20">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 font-display mb-1">All Items</h3>
                    <p class="text-sm text-gray-600">Manage all lost and found items</p>
                </div>
               
            </div>
            <div>
                <table class="w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date and Time</th>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody" class="bg-white divide-y divide-gray-200">
                        <?php foreach($allItems as $item): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200 item-card">
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900"><?= esc($item['title']) ?></div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <?php 
                                $imageUrl = '';
                                if (isset($item['images']) && $item['images']) {
                                    // Check if it's base64 data
                                    if (strpos($item['images'], 'data:image') === 0) {
                                        // It's base64 data, use directly
                                        $imageUrl = $item['images'];
                                    } else {
                                        // Try to decode as JSON first
                                        $decodedImages = json_decode($item['images'], true);
                                        if (is_array($decodedImages) && !empty($decodedImages)) {
                                            $firstImage = $decodedImages[0];
                                            
                                            // Check if it's a full URL (starts with http)
                                            if (strpos($firstImage, 'http') === 0) {
                                                $imageUrl = $firstImage;
                                            }
                                            // Check if it's base64 data without data URI prefix
                                            else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                // It's base64 data without prefix, add the data URI prefix
                                                $imageUrl = 'data:image/jpeg;base64,' . $firstImage;
                                            }
                                            // It's a filename, construct the local URL
                                            else {
                                                $imageUrl = base_url('uploads/' . $firstImage);
                                            }
                                        } else {
                                            // Not JSON, check if it's a direct URL or filename
                                            if (strpos($item['images'], 'http') === 0) {
                                                $imageUrl = $item['images'];
                                            } else {
                                                $imageUrl = base_url('uploads/' . $item['images']);
                                            }
                                        }
                                    }
                                }
                                ?>
                                <?php if ($imageUrl): ?>
                                    <div class="h-8 w-8 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                                            <img src="<?= $imageUrl ?>" 
                                                alt="<?= esc($item['title']) ?>" 
                                                class="h-full w-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <?php 
                                    $type = trim(strtolower($item['type'] ?? ''));
                                    if ($type == 'lost') {
                                        $type_class = 'bg-red-100 text-red-800';
                                    } else if ($type == 'found') {
                                        $type_class = 'bg-green-100 text-green-800';
                                    } else {
                                        $type_class = 'bg-gray-100 text-gray-800';
                                    }
                                ?>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium <?= $type_class ?>">
                                    <?= esc(ucfirst($item['type'] ?? 'Unknown')) ?>
                                </span>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500"><?= esc($item['location'] ?? 'N/A') ?></td>
                            <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500"><?= date('M d, Y', strtotime($item['date_lost_found'] ?? $item['created_at'])) ?></td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <?php 
                                    $status = trim(strtolower($item['status'] ?? ''));
                                    $statusLabels = [
                                        'active' => 'Pending Cases',
                                        'inactive' => 'Archived',
                                        'claimed' => 'Case Closed',
                                        'returned' => 'Successfully Returned',
                                        'pending' => 'Pending Review',
                                        'pending turning-in' => 'Pending Turning-in',
                                        'fake report' => 'Fake Report'
                                    ];
                                    
                                    if ($status == 'pending' || $status == 'active') {
                                        $status_class = 'bg-yellow-100 text-yellow-800';
                                    } else if ($status == 'claimed') {
                                        $status_class = 'bg-green-100 text-green-800';
                                    } else if ($status == 'returned') {
                                        $status_class = 'bg-blue-100 text-blue-800';
                                    } else if ($status == 'inactive') {
                                        $status_class = 'bg-gray-100 text-gray-800';
                                    } else if ($status == 'pending turning-in') {
                                        $status_class = 'bg-orange-100 text-orange-800';
                                    } else if ($status == 'fake report') {
                                        $status_class = 'bg-red-100 text-red-800';
                                    } else {
                                        $status_class = 'bg-gray-100 text-gray-800';
                                    }
                                    
                                    $displayStatus = $statusLabels[$status] ?? ucfirst($status);
                                ?>
                                <div class="flex flex-col items-start">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium <?= $status_class ?>">
                                        <?= esc($displayStatus) ?>
                                    </span>
                                    <?php if (($status == 'claimed' || $status == 'returned') && !empty($item['updated_at'])): ?>
                                        <span class="text-xs text-gray-500 mt-1">
                                            <?= date('M d, Y g:i A', strtotime($item['updated_at'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-center text-xs font-medium">
                                <div class="item-actions">
                                    <a href="<?= site_url('items/view/' . $item['id']) ?>" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="View">
                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-medium rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="Edit">
                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete('<?= $item['id'] ?>')" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="Delete">
                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($allItems)): ?>
                            <tr>
                                <td colspan="8" class="px-2 py-6 text-center text-xs text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No items found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter criteria</p>
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
                        <?= $pager->links('items', 'default_full') ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .search-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        border-color: #3b82f6; /* Blue focus ring */
    }
</style>
<script>
    function confirmDelete(itemId) {
        if (confirm('Are you sure you want to delete this item?')) {
            // Create a form and submit it to maintain session
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('items/delete/') ?>' + itemId;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);
            
            // Add method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }



    // Debounce function
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

    // Loading indicator functions
    function showSearchLoading() {
        const searchIcon = document.getElementById('searchIcon');
        const searchSpinner = document.getElementById('searchSpinner');
        if (searchIcon) {
            searchIcon.classList.add('d-none');
        }
        if (searchSpinner) {
            searchSpinner.classList.remove('d-none');
        }
    }

    function hideSearchLoading() {
        const searchIcon = document.getElementById('searchIcon');
        const searchSpinner = document.getElementById('searchSpinner');
        if (searchIcon) {
            searchIcon.classList.remove('d-none');
        }
        if (searchSpinner) {
            searchSpinner.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Ensure initial icon/spinner state
        hideSearchLoading();

        // Search functionality
        const headerSearch = document.getElementById('headerSearch');
        const statusFilter = document.getElementById('status');
        const typeFilter = document.getElementById('type');
        const searchIconEl = document.getElementById('searchIcon');
        
        // Match claims: keep icon gray; no focus color change
        // (Spinner indicates active search)
        // Removed focus/blur listeners that turned the icon blue
        if (headerSearch && searchIconEl) {
            headerSearch.addEventListener('focus', function() {
                searchIconEl.classList.add('text-blue-600');
            });
            headerSearch.addEventListener('blur', function() {
                searchIconEl.classList.remove('text-blue-600');
            });
        }

        // Create debounced version of filterItems for search input
        function filterItemsWithLoading() {
            showSearchLoading();
            filterItems();
            // Hide loading after a short delay to show the filtering is complete
            setTimeout(hideSearchLoading, 300);
        }
        const debouncedFilterItems = debounce(filterItemsWithLoading, 500);

        // Add event listeners for search and filters
        if (headerSearch) {
            headerSearch.addEventListener('input', function() {
                const searchValue = this.value.trim();
                if (searchValue.length > 0) {
                    showSearchLoading();
                } else {
                    hideSearchLoading();
                }
                debouncedFilterItems();
            });
        }
        if (statusFilter) {
            statusFilter.addEventListener('change', filterItemsWithLoading);
        }
        if (typeFilter) {
            typeFilter.addEventListener('change', filterItemsWithLoading);
        }
        
        // Per page filter
        const perPageFilter = document.getElementById('per_page');
        if (perPageFilter) {
            perPageFilter.addEventListener('change', filterItemsWithLoading);
        }

        function filterItems() {
            const headerSearch = document.getElementById('headerSearch');
            const statusFilter = document.getElementById('status');
            const typeFilter = document.getElementById('type');
            
            const searchTerm = headerSearch ? headerSearch.value.toLowerCase().trim() : '';
            const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : '';
            const selectedType = typeFilter ? typeFilter.value.toLowerCase() : '';

            const rows = document.querySelectorAll('#itemsTableBody tr');

            rows.forEach(row => {
                if (row.querySelector('td[colspan]')) {
                    // Skip "No items found" row
                    return;
                }

                const title = row.querySelector('td:first-child .text-xs')?.textContent.toLowerCase() || '';
                const type = row.querySelector('td:nth-child(3) span')?.textContent.toLowerCase() || '';
                const location = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                const status = row.querySelector('td:nth-child(6) span')?.textContent.toLowerCase() || '';

                const matchesSearch = !searchTerm || 
                    title.includes(searchTerm) || 
                    location.includes(searchTerm) ||
                    type.includes(searchTerm) ||
                    status.includes(searchTerm);

                const matchesStatus = !selectedStatus || status.includes(selectedStatus);
                const matchesType = !selectedType || type.includes(selectedType);

                row.style.display = (matchesSearch && matchesStatus && matchesType) ? '' : 'none';
            });
        }

        // Auto-submit search on input clear
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value === '') {
                    window.location.href = '<?= site_url('items') ?>';
                }
            });
        }

        // Auto-submit filters on change
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            const filterSelects = filterForm.querySelectorAll('select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        }
        
        // Add enhanced hover effects to action buttons
        const actionButtons = document.querySelectorAll('.action-btn');
        
        // Add keyframe animations for button effects
        if (!document.getElementById('action-btn-animations')) {
            const styleSheet = document.createElement('style');
            styleSheet.id = 'action-btn-animations';
            styleSheet.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(0.95); }
                    100% { transform: scale(1); }
                }
                
                @keyframes float {
                    0% { transform: translateY(0); }
                    50% { transform: translateY(-5px); }
                    100% { transform: translateY(0); }
                }
                
                @keyframes glow {
                    0% { box-shadow: 0 0 0 rgba(255,255,255,0); }
                    50% { box-shadow: 0 0 10px rgba(255,255,255,0.5); }
                    100% { box-shadow: 0 0 0 rgba(255,255,255,0); }
                }
                
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(styleSheet);
        }
        
        // Apply enhanced animations to each button
        actionButtons.forEach(btn => {
            // Get button type
            const isViewBtn = btn.classList.contains('view-btn');
            const isEditBtn = btn.classList.contains('edit-btn');
            const isDeleteBtn = btn.classList.contains('delete-btn');
            
            // Get SVG icon
            const icon = btn.querySelector('svg');
            
            // Mouse enter effect
            btn.addEventListener('mouseenter', function() {
                // Common effects
                if (icon) {
                    icon.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    icon.style.transform = 'scale(1.2)';
                }
                
                // Button-specific effects
                if (isViewBtn) {
                    // Add subtle rotation for view button
                    icon.style.animation = 'spin 4s linear infinite';
                } else if (isEditBtn) {
                    // Add subtle float animation for edit button
                    btn.style.animation = 'float 2s ease-in-out infinite';
                } else if (isDeleteBtn) {
                    // Add subtle glow for delete button
                    btn.style.animation = 'glow 1.5s ease-in-out infinite';
                }
            });
            
            // Mouse leave effect
            btn.addEventListener('mouseleave', function() {
                if (icon) {
                    icon.style.transform = 'scale(1)';
                    icon.style.animation = 'none';
                }
                btn.style.animation = 'none';
            });
            
            // Click effect
            btn.addEventListener('click', function(e) {
                // Don't apply to delete button as it has a confirmation
                if (isDeleteBtn && e.currentTarget.tagName.toLowerCase() === 'button') {
                    return; // Let the confirmDelete function handle it
                }
                
                // Create ripple effect
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                
                // Position the ripple where clicked
                const rect = btn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height) * 2;
                ripple.style.width = ripple.style.height = `${size}px`;
                ripple.style.left = `${e.clientX - rect.left - size/2}px`;
                ripple.style.top = `${e.clientY - rect.top - size/2}px`;
                
                // Add ripple to button
                btn.appendChild(ripple);
                
                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                }, 600);
                
                // Add pulse animation
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = 'pulse 0.5s';
                }, 10);
            });
        });
        
        // Add ripple effect styles
        if (!document.getElementById('ripple-styles')) {
            const rippleStyle = document.createElement('style');
            rippleStyle.id = 'ripple-styles';
            rippleStyle.textContent = `
                .action-btn {
                    position: relative;
                    overflow: hidden;
                }
                
                .ripple-effect {
                    position: absolute;
                    border-radius: 50%;
                    background-color: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                }
                
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(rippleStyle);
        }
    });
</script>
<?= $this->endSection() ?>
