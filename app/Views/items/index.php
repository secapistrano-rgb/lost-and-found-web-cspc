<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Item Management
<?= $this->endSection() ?>

<script>
// Simple and Effective Search and Filter Functionality (like Claims Management)
document.addEventListener('DOMContentLoaded', function() {
    // Combined filter function
    function filterItems() {
        const categoryFilter = document.getElementById('categoryFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const mainSearch = document.getElementById('mainSearch').value.toLowerCase().trim();
        const headerSearch = document.getElementById('headerSearch').value.toLowerCase().trim();
        const searchTerm = mainSearch || headerSearch;
        
        const rows = document.querySelectorAll('#itemsTableBody tr');
        
        rows.forEach(row => {
            // Skip empty state rows
            if (!row.querySelector('td') || row.querySelector('td').colSpan > 1) {
                row.style.display = '';
                return;
            }
            
            let showRow = true;
            
            // Main search filter (searches across all visible text in the row)
            if (searchTerm && showRow) {
                const rowText = row.textContent.toLowerCase();
                if (!rowText.includes(searchTerm)) {
                    showRow = false;
                }
            }
            
            // Category filter
            if (categoryFilter && showRow) {
                const categoryCell = row.querySelector('td:nth-child(3)');
                if (categoryCell) {
                    const categoryText = categoryCell.textContent.toLowerCase().trim();
                    if (!categoryText.includes(categoryFilter.toLowerCase())) {
                        showRow = false;
                    }
                }
            }
            
            // Status filter
            if (statusFilter && showRow) {
                const statusCell = row.querySelector('td:nth-child(8)');
                if (statusCell) {
                    const statusText = statusCell.textContent.toLowerCase().trim();
                    if (!statusText.includes(statusFilter.toLowerCase())) {
                        showRow = false;
                    }
                }
            }
            
            // Type filter
            if (typeFilter && showRow) {
                const typeCell = row.querySelector('td:nth-child(4)');
                if (typeCell) {
                    const typeText = typeCell.textContent.toLowerCase().trim();
                    if (!typeText.includes(typeFilter.toLowerCase())) {
                        showRow = false;
                    }
                }
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    // Event listeners
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const mainSearch = document.getElementById('mainSearch');
    const headerSearch = document.getElementById('headerSearch');
    const clearFilters = document.getElementById('clearFilters');
    
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
    function showSearchLoading(searchType) {
        if (searchType === 'header') {
            const searchIcon = document.getElementById('headerSearchIcon');
            const searchSpinner = document.getElementById('headerSearchSpinner');
            if (searchIcon && searchSpinner) {
                searchIcon.classList.add('hidden');
                searchSpinner.classList.remove('hidden');
            }
        } else if (searchType === 'main') {
            const searchIcon = document.getElementById('mainSearchIcon');
            const searchSpinner = document.getElementById('mainSearchSpinner');
            if (searchIcon && searchSpinner) {
                searchIcon.classList.add('hidden');
                searchSpinner.classList.remove('hidden');
            }
        }
    }

    function hideSearchLoading(searchType) {
        if (searchType === 'header') {
            const searchIcon = document.getElementById('headerSearchIcon');
            const searchSpinner = document.getElementById('headerSearchSpinner');
            if (searchIcon && searchSpinner) {
                searchIcon.classList.remove('hidden');
                searchSpinner.classList.add('hidden');
            }
        } else if (searchType === 'main') {
            const searchIcon = document.getElementById('mainSearchIcon');
            const searchSpinner = document.getElementById('mainSearchSpinner');
            if (searchIcon && searchSpinner) {
                searchIcon.classList.remove('hidden');
                searchSpinner.classList.add('hidden');
            }
        }
    }

    // Enhanced filter function with loading indicator
    function filterItemsWithLoading() {
        filterItems();
        // Hide loading after a short delay to show the filtering is complete
        setTimeout(() => {
            hideSearchLoading('header');
            hideSearchLoading('main');
        }, 300);
    }

    // Debounced search function
    const debouncedSearch = debounce(filterItemsWithLoading, 500);

    if (categoryFilter) categoryFilter.addEventListener('change', filterItemsWithLoading);
    if (statusFilter) statusFilter.addEventListener('change', filterItemsWithLoading);
    if (typeFilter) typeFilter.addEventListener('change', filterItemsWithLoading);
    
    // Per page filter
    const perPageFilter = document.getElementById('perPageFilter');
    if (perPageFilter) perPageFilter.addEventListener('change', filterItemsWithLoading);
    
    // Enhanced search inputs with automatic filtering
    if (mainSearch) {
        mainSearch.addEventListener('input', function() {
            const searchValue = this.value.trim();
            if (searchValue.length > 0) {
                showSearchLoading('main');
            } else {
                hideSearchLoading('main');
            }
            debouncedSearch();
        });
    }
    
    if (headerSearch) {
        headerSearch.addEventListener('input', function() {
            const searchValue = this.value.trim();
            if (searchValue.length > 0) {
                showSearchLoading('header');
            } else {
                hideSearchLoading('header');
            }
            debouncedSearch();
        });
    }
    
    // Clear filters functionality
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            if (mainSearch) mainSearch.value = '';
            if (headerSearch) headerSearch.value = '';
            if (categoryFilter) categoryFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            if (typeFilter) typeFilter.value = '';
            filterItems();
            
            // Add visual feedback
            this.style.transform = 'rotate(180deg)';
            setTimeout(() => {
                this.style.transform = 'rotate(0deg)';
            }, 300);
        });
    }
    
    // Add focus styling for search input
    if (mainSearch) {
        mainSearch.addEventListener('focus', function() {
            this.style.borderColor = '#0d6efd';
            this.style.boxShadow = '0 0 0 0.2rem rgba(13, 110, 253, 0.25)';
        });
        
        mainSearch.addEventListener('blur', function() {
            this.style.borderColor = '#e9ecef';
            this.style.boxShadow = 'none';
        });
    }
});
</script>

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
    
    .item-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    .item-placeholder {
        width: 50px;
        height: 50px;
        background: rgba(248, 249, 250, 0.8);
        border: 2px dashed rgba(222, 226, 230, 0.6);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    .item-link {
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
    }
    .item-link:hover {
        color: #3b82f6;
        text-decoration: none;
    }

    /* Status badges with gradients */
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .status-approved {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .status-resolved {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }
    
    .status-claimed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-default {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    .type-lost {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .type-found {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .type-default {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    /* Action buttons with gradients */
    .btn-action-view {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
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
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-action-view {
        background-color: #00B2D4;
        color: black !important;
        border: none;
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    .btn-action-edit {
        background-color: #FFC107;
        color: black !important;
        border: none;
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    .btn-action-delete {
        background-color: #E53935;
        color: white !important;
        border: none;
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    .btn-group.btn-group-sm > .btn {
        padding: .2rem .4rem;
        font-size: .8rem;
    }
    .btn-group .btn:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }
    .btn-group {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: .4rem;
    }
    .btn-group .btn {
        border: none;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: .4rem;
        border-bottom-left-radius: .4rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: .4rem;
        border-bottom-right-radius: .4rem;
    }
    
    /* New styles for claim relationship indicators */
    .claim-indicator {
        font-size: 0.8rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-top: 4px;
        display: inline-block;
    }
    .has-claims {
        background-color: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }
    .no-claims {
        background-color: #f3e5f5;
        color: #7b1fa2;
        border: 1px solid #e1bee7;
    }
    .claimed-item {
        background-color: #e8f5e8;
        border-left: 4px solid #4caf50;
    }
    .pending-item {
        background-color: #fff8e1;
        border-left: 4px solid #ff9800;
    }

    /* Enhanced Search Styling */
    #mainSearch {
        transition: all 0.3s ease;
        font-size: 1rem;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
    }
    
    #mainSearch:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        transform: translateY(-1px);
    }
    
    #mainSearch::placeholder {
        color: #6c757d;
        font-style: italic;
    }
    
    .search-container {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
        transition: color 0.3s ease;
    }
    
    #mainSearch:focus + .search-icon {
        color: #0d6efd;
    }
    
    /* Clear button animation */
    #clearFilters {
        transition: all 0.3s ease;
    }
    
    #clearFilters:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: scale(1.05);
    }
    
    /* Search result highlighting */
    .search-highlight {
        background-color: #fff3cd;
        padding: 0.1rem 0.2rem;
        border-radius: 0.2rem;
        font-weight: 500;
    }
    
    /* Filter section enhancements */
    .responsive-filters-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
    }
    
    .responsive-filter-group label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-select {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* Table styling improvements */
    .table-responsive {
        overflow-x: auto;
        border-radius: 12px;
    }
    
    .table {
        margin-bottom: 0;
        width: 100%;
        max-width: 100%;
    }
    
    .table th {
        white-space: nowrap;
        padding: 4px 3px;
        border-bottom: 2px solid rgba(222, 226, 230, 0.6);
        background: rgba(248, 249, 250, 0.8);
        color: var(--cspc-dark);
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .table td {
        padding: 4px 3px;
        border-bottom: 1px solid rgba(222, 226, 230, 0.4);
        vertical-align: middle;
        font-size: 0.75rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    /* Column width optimization - let content shrink naturally */
    .table th:nth-child(1), .table td:nth-child(1) { width: 3%; } /* ID */
    .table th:nth-child(2), .table td:nth-child(2) { width: 15%; } /* Title & Image */
    .table th:nth-child(3), .table td:nth-child(3) { width: 8%; } /* Category */
    .table th:nth-child(4), .table td:nth-child(4) { width: 6%; } /* Type */
    .table th:nth-child(5), .table td:nth-child(5) { width: 10%; } /* Location */
    .table th:nth-child(6), .table td:nth-child(6) { width: 8%; } /* Date */
    .table th:nth-child(7), .table td:nth-child(7) { width: 10%; } /* Status */
    .table th:nth-child(8), .table td:nth-child(8) { white-space: nowrap; } /* Actions */
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .table {
            font-size: 0.9rem;
        }
        .table th, .table td {
            padding: 8px 6px;
        }
    }
    
    @media (max-width: 768px) {
        .table {
            font-size: 0.8rem;
        }
        .table th, .table td {
            padding: 6px 4px;
        }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <!-- Compact Header Section -->
    <div class="card border-0 shadow-sm mb-4 responsive-header-card">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="mb-3 mb-lg-0">
                        <h1 class="display-6 display-md-5 fw-bold text-dark mb-2 responsive-page-title">
                            <span class="text-gradient">Item Management</span>
                        </h1>
                        <p class="lead text-muted mb-3 responsive-page-subtitle">Monitor and manage lost & found items</p>
                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 small text-muted">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                <span class="responsive-status-text">Live Updates</span>
                            </div>
                            <div class="responsive-timestamp">Last Updated: <?= date('M d, Y \\a\\t g:i A') ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="col-12 col-lg-4">
                    <div class="position-relative responsive-search-container">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg id="headerSearchIcon" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <div id="headerSearchSpinner" class="h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin hidden"></div>
                        </div>
                        <input type="text" id="headerSearch" placeholder="Search items (auto-search enabled)..." 
                               class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="glass-card backdrop-blur-md bg-green-50/90 border border-green-200/50 rounded-xl shadow-lg mb-6 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-500 rounded-full">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-green-800 font-medium"><?= session()->getFlashdata('success') ?></p>
            </div>
        </div>
    <?php endif; ?></div>

    <!-- Compact Filter Section -->
    <div class="card border-0 shadow-sm mb-4 responsive-filters-card">
        <div class="card-body p-3 p-md-4">
            <div class="row g-3 align-items-end">
                <!-- Enhanced Search Bar -->
                <div class="col-12 col-lg-4 responsive-filter-group">
                    <label class="form-label fw-semibold text-dark small">Search Items</label>
                    <div class="position-relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg id="mainSearchIcon" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <div id="mainSearchSpinner" class="h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin hidden"></div>
                        </div>
                        <input type="text" id="mainSearch" placeholder="Search items (auto-search enabled)..." 
                               class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-6 col-lg-2 responsive-filter-group">
                    <label class="form-label fw-semibold text-dark small">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="accessories">Accessories</option>
                        <option value="documents">Documents</option>
                        <option value="keys">Keys</option>
                        <option value="bags">Bags</option>
                        <option value="jewelry">Jewelry</option>
                        <option value="books">Books</option>
                        <option value="sports">Sports Equipment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-lg-2 responsive-filter-group">
                    <label class="form-label fw-semibold text-dark small">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="claimed">Claimed</option>
                        <option value="resolved">Resolved</option>
                        <option value="pending turning-in">Pending Turning-in</option>
                        <option value="fake report">Fake Report</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div class="col-6 col-lg-2 responsive-filter-group">
                    <label class="form-label fw-semibold text-dark small">Type</label>
                    <select class="form-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="lost">Lost</option>
                        <option value="found">Found</option>
                    </select>
                </div>

                <!-- Per Page Filter -->
                <div class="col-6 col-lg-2 responsive-filter-group">
                    <label class="form-label fw-semibold text-dark small">Per Page</label>
                    <select id="perPageFilter" class="form-select rounded-3 shadow-sm" style="border: 1px solid #dce4ec;">
                        <option value="25" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '25') ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= (!isset($_GET['per_page']) || $_GET['per_page'] == '50') ? 'selected' : '' ?>>50</option>
                        <option value="75" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '75') ? 'selected' : '' ?>>75</option>
                        <option value="100" <?= (isset($_GET['per_page']) && $_GET['per_page'] == '100') ? 'selected' : '' ?>>100</option>
                    </select>
                </div>

                <!-- Clear Filters Button -->
                <div class="col-6 col-lg-2 responsive-filter-group">
                    <label class="form-label fw-semibold text-dark small">&nbsp;</label>
                    <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100" 
                            style="border-radius: 8px;" title="Clear all filters">
                        <i class="bi bi-arrow-clockwise me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Items Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="max-height: none;">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h2 class="text-lg font-semibold text-white">All Items</h2>
            </div>
        </div>
        
        <!-- Items Grid -->
        <div class="card border-0 shadow-sm responsive-items-container">
            <!-- Desktop Table View -->
            <div class="d-none d-lg-block">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold text-dark small">ID</th>
                                <th class="fw-semibold text-dark small">Title & Image</th>
                                <th class="fw-semibold text-dark small">Category</th>
                                <th class="fw-semibold text-dark small">Type</th>
                                <th class="fw-semibold text-dark small">Location</th>
                                <th class="fw-semibold text-dark small">Date</th>
                                <th class="fw-semibold text-dark small">Status</th>
                                <th class="fw-semibold text-dark small text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody" class="responsive-items-tbody">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-1 py-1 text-nowrap" style="font-size: 0.7rem;">
                                    <?= esc($item['id']) ?>
                                </td>
                                <td class="px-1 py-1 text-nowrap">
                                    <div class="d-flex align-items-center" style="gap: 4px;">
                                        <div class="flex-shrink-0">
                                        <?php 
                                        $imageUrl = '';
                                        if (!empty($item['images'])) {
                                            $imageData = $item['images'];
                                            
                                            // Enhanced image URL handling logic
                                            if (strpos($imageData, 'data:image') === 0) {
                                                // Base64 encoded image with data URI prefix
                                                // For very long base64 strings, show placeholder instead
                                                if (strlen($imageData) > 1000) {
                                                    $imageUrl = ''; // Will show placeholder
                                                } else {
                                                    $imageUrl = $imageData;
                                                }
                                            } else {
                                                // Try to decode as JSON first
                                                $decodedImages = json_decode($imageData, true);
                                                if (is_array($decodedImages) && !empty($decodedImages)) {
                                                    $firstImage = $decodedImages[0];
                                                    
                                                    // Check if it's a full URL (starts with http)
                                                    if (strpos($firstImage, 'http') === 0) {
                                                        $imageUrl = $firstImage;
                                                    }
                                                    // Check if it's base64 data without data URI prefix
                                                    else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                        // Skip base64 data to avoid URL encoding issues in thumbnails
                                                        $imageUrl = '';
                                                    }
                                                    // It's a filename, construct the local URL
                                                    else {
                                                        $imageUrl = base_url('uploads/' . $firstImage);
                                                    }
                                                } else {
                                                    // Not JSON, check if it's a direct URL or filename
                                                    if (strpos($imageData, 'http') === 0) {
                                                        $imageUrl = $imageData;
                                                    } else if (strpos($imageData, '.') !== false && strlen($imageData) < 100) {
                                                        $imageUrl = base_url('uploads/' . $imageData);
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                            <?php if ($imageUrl): ?>
                                                <img src="<?= $imageUrl ?>" alt="<?= esc($item['title']) ?>" class="rounded" style="width: 24px; height: 24px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                    <svg class="text-muted" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size: 0.7rem;">
                                            <div class="text-dark text-truncate" style="max-width: 90px;" title="<?= esc($item['title']) ?>"><?= esc($item['title']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-1 py-1 text-nowrap" style="font-size: 0.7rem;">
                                    <?= esc($item['category']) ?>
                                </td>
                                <td class="px-1 py-1 text-nowrap">
                                    <?php 
                                        $type_class = 'type-default';
                                        $type = trim(strtolower($item['type'] ?? ''));
                                        if ($type == 'lost') {
                                            $type_class = 'type-lost';
                                        } else if ($type == 'found') {
                                            $type_class = 'type-found';
                                        }
                                    ?>
                                    <span class="badge <?= $type_class ?>" style="font-size: 0.65rem; padding: 1px 4px;">
                                        <?= esc(ucfirst($item['type'] ?? '')) ?>
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-nowrap text-truncate" style="font-size: 0.7rem; max-width: 80px;" title="<?= esc($item['location'] ?? 'N/A') ?>">
                                    <?= esc($item['location'] ?? 'N/A') ?>
                                </td>
                                <td class="px-1 py-1 text-nowrap" style="font-size: 0.7rem;">
                                    <?= date('M d', strtotime($item['created_at'])) ?>
                                </td>
                                <td class="px-1 py-1 text-nowrap">
                                    <div class="d-flex flex-column" style="gap: 2px;">
                                        <?php 
                                            $status_class = 'status-default';
                                            $status = trim(strtolower($item['status'] ?? ''));
                                            if ($status == 'pending') {
                                                $status_class = 'status-pending';
                                                $status_text = 'Pending';
                                            } else if ($status == 'claimed') {
                                                $status_class = 'status-claimed';
                                                $status_text = 'Awaiting Pickup';
                                            } else if ($status == 'returned') {
                                                $status_class = 'status-returned';
                                                $status_text = 'Successfully Returned';
                                            } else {
                                                $status_text = ucfirst($status);
                                            }
                                        ?>
                                        <span class="badge <?= $status_class ?>" style="font-size: 0.65rem; padding: 2px 4px;">
                                            <?= esc($status_text) ?>
                                        </span>
                                        
                                        <!-- Claim relationship indicator -->
                                        <?php if (isset($item['claim_count']) && $item['claim_count'] > 0): ?>
                                            <span style="font-size: 0.6rem; color: #6c757d;"><?= $item['claim_count'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <div class="d-flex justify-content-center" style="gap: 3px;">
                                        <a href="<?= base_url('items/view/' . $item['id']) ?>" 
                                           class="btn btn-sm" style="background-color: #00B2D4; color: black; padding: 0.2rem 0.4rem; font-size: 0.7rem; line-height: 1;"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('items/edit/' . $item['id']) ?>" 
                                           class="btn btn-sm" style="background-color: #FFC107; color: black; padding: 0.2rem 0.4rem; font-size: 0.7rem; line-height: 1;"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('items/delete/' . $item['id']) ?>" 
                                           class="btn btn-sm" style="background-color: #E53935; color: white; padding: 0.2rem 0.4rem; font-size: 0.7rem; line-height: 1;"
                                           title="Delete" 
                                           onclick="return confirm('Are you sure you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                        <h5 class="text-dark mb-2">No items found</h5>
                                        <p class="text-muted small">No items are currently available in the system.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Mobile Card View -->
            <div class="d-lg-none p-3">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <div class="card border-0 shadow-sm mb-3 responsive-item-card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <?php 
                                        $imageUrl = '';
                                        if (!empty($item['images'])) {
                                            $imageData = $item['images'];
                                            
                                            // Enhanced image URL handling logic
                                            if (strpos($imageData, 'data:image') === 0) {
                                                if (strlen($imageData) > 1000) {
                                                    $imageUrl = '';
                                                } else {
                                                    $imageUrl = $imageData;
                                                }
                                            } else {
                                                $decodedImages = json_decode($imageData, true);
                                                if (is_array($decodedImages) && !empty($decodedImages)) {
                                                    $firstImage = $decodedImages[0];
                                                    
                                                    if (strpos($firstImage, 'http') === 0) {
                                                        $imageUrl = $firstImage;
                                                    } else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                        $imageUrl = '';
                                                    } else {
                                                        $imageUrl = base_url('uploads/' . $firstImage);
                                                    }
                                                } else {
                                                    if (strpos($imageData, 'http') === 0) {
                                                        $imageUrl = $imageData;
                                                    } else if (strpos($imageData, '.') !== false && strlen($imageData) < 100) {
                                                        $imageUrl = base_url('uploads/' . $imageData);
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <?php if ($imageUrl): ?>
                                            <img src="<?= $imageUrl ?>" alt="<?= esc($item['title']) ?>" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-semibold text-dark mb-1"><?= esc($item['title']) ?></h6>
                                                <p class="text-muted small mb-0">ID: #<?= str_pad(esc($item['id']), 4, '0', STR_PAD_LEFT) ?></p>
                                            </div>
                                            <div class="d-flex flex-column align-items-end gap-1">
                                                <?php 
                                                    $status_class = 'bg-secondary';
                                                    $status = trim(strtolower($item['status'] ?? ''));
                                                    if ($status == 'pending') {
                                                        $status_class = 'bg-warning';
                                                        $status_text = 'Pending';
                                                    } else if ($status == 'claimed') {
                                                        $status_class = 'bg-success';
                                                        $status_text = 'Awaiting Pickup';
                                                    } else if ($status == 'returned') {
                                                        $status_class = 'bg-info';
                                                        $status_text = 'Successfully Returned';
                                                    } else {
                                                        $status_text = ucfirst($status);
                                                    }
                                                ?>
                                                <span class="badge <?= $status_class ?> small">
                                                    <?= esc($status_text) ?>
                                                </span>
                                                <?php 
                                                    $type_class = 'bg-secondary';
                                                    $type = trim(strtolower($item['type'] ?? ''));
                                                    if ($type == 'lost') {
                                                        $type_class = 'bg-danger';
                                                    } else if ($type == 'found') {
                                                        $type_class = 'bg-primary';
                                                    }
                                                ?>
                                                <span class="badge <?= $type_class ?> small">
                                                    <?= esc(ucfirst($item['type'] ?? '')) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-6">
                                                <small class="text-muted"><strong>Category:</strong> <?= esc($item['category']) ?></small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted"><strong>Location:</strong> <?= esc($item['location'] ?? 'N/A') ?></small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted"><strong>Date:</strong> <?= date('M d, Y', strtotime($item['created_at'])) ?></small>
                                            </div>
                                            <?php if (isset($item['claim_count']) && $item['claim_count'] > 0): ?>
                                                <div class="col-6">
                                                    <small class="text-muted"><strong>Claims:</strong> <?= $item['claim_count'] ?></small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-end gap-2">
                                            <a href="<?= base_url('items/view/' . $item['id']) ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                            <a href="<?= base_url('items/edit/' . $item['id']) ?>" class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                        <h5 class="text-dark mb-2">No items found</h5>
                        <p class="text-muted small">No items are currently available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
