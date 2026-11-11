<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Claims Management
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
    .claim-item-link {
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
    }
    .claim-item-link:hover {
        color: #3b82f6;
        text-decoration: none;
    }
    .relationship-indicator {
        border-left: 3px solid #3b82f6;
        padding-left: 10px;
        margin-left: 5px;
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
    
    .status-default {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    /* Action buttons styled via inline utility classes to match Match Management exactly. Removed previous .btn-action-view block to avoid conflicts. */
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
                <div class="space-y-3">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Claims Management</h1>
                    <p class="text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Monitor and manage lost & found claims</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs">Live Updates</span>
                        </div>
                        <div class="text-xs">Last Updated: <?= date('M d, Y \\a\\t g:i A') ?></div>
                    </div>
                </div>
                

            </div>
        </div>

        <!-- Filters and Search -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-lg mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Category Filter -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Category</label>
                        <select class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="categoryFilter">
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
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Status</label>
                        <select class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Denied</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Type</label>
                        <select class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="lost">Lost Items</option>
                            <option value="found">Found Items</option>
                            <option value="matched">Matched Items</option>
                        </select>
                    </div>

                    <!-- Search Field -->
                    <div class="space-y-2">
                        <label for="headerSearch" class="block text-sm font-semibold text-gray-700">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i id="searchIcon" class="bi bi-search text-gray-400"></i>
                                <div id="searchSpinner" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                    <span class="visually-hidden">Searching...</span>
                                </div>
                            </div>
                            <input type="text" id="headerSearch" placeholder="Search..." class="w-full pl-10 pr-10 py-3 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" value="<?= esc($search ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claims Grid -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-lg">
            <!-- Desktop Table View -->
            <div class="hidden lg:block">
                <div class="overflow-hidden rounded-2xl" style="max-height: none; overflow-y: visible;">
                    <table class="w-full">
                        <thead class="bg-gray-50/80 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Match & Claim Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Claimant</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status & Timeline</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="claimsTableBody" class="bg-white/50 backdrop-blur-sm divide-y divide-gray-200">
                        <?php if (!empty($claims)): ?>
                            <?php foreach($claims as $index => $claim): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-900"><?= $index + 1 ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-4">
                                        <!-- Match thumbnails -->
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <!-- Lost Item Image -->
                                                <div class="text-center">
                                                    <?php if (!empty($claim['lost_item_images'])): ?>
                                                        <?php 
                                                        $imageData = $claim['lost_item_images'];
                                                        $imageSrc = '';
                                                        
                                                        // Enhanced image URL handling logic
                                                        if (strpos($imageData, 'data:image') === 0) {
                                                            // Base64 encoded image with data URI prefix
                                                            $imageSrc = $imageData;
                                                        } else {
                                                            // Try to decode as JSON first
                                                            $decodedImages = json_decode($imageData, true);
                                                            if (is_array($decodedImages) && !empty($decodedImages)) {
                                                                $firstImage = $decodedImages[0];
                                                                
                                                                // Check if it's a full URL (starts with http)
                                                                if (strpos($firstImage, 'http') === 0) {
                                                                    $imageSrc = $firstImage;
                                                                }
                                                                // Check if it's base64 data without data URI prefix
                                                                else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                                    // It's base64 data without prefix, add the data URI prefix
                                                                    $imageSrc = 'data:image/jpeg;base64,' . $firstImage;
                                                                }
                                                                // It's a filename, construct the local URL
                                                                else {
                                                                    $imageSrc = base_url('uploads/' . $firstImage);
                                                                }
                                                            } else {
                                                                // Not JSON, check if it's a direct URL or filename
                                                                if (strpos($imageData, 'http') === 0) {
                                                                    $imageSrc = $imageData;
                                                                } else if (strpos($imageData, '/9j/') === 0 || strpos($imageData, 'iVBORw0KGgo') === 0) {
                                                                    $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                                                                } else {
                                                                    $imageSrc = base_url('uploads/' . $imageData);
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <img src="<?= $imageSrc ?>" 
                                                             alt="Lost: <?= esc($claim['lost_item_title']) ?>" 
                                                             class="item-thumbnail">
                                                    <?php else: ?>
                                                        <div class="item-placeholder">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                    <span class="text-xs text-gray-500 block mt-1">Lost</span>
                                                </div>
                                                
                                                <!-- Match indicator -->
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                    </svg>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                                                        <?= number_format($claim['score'] * 100, 0) ?>%
                                                    </span>
                                                </div>
                                                
                                                <!-- Found Item Image -->
                                                <div class="text-center">
                                                    <?php if (!empty($claim['found_item_images'])): ?>
                                                        <?php 
                                                        $imageData = $claim['found_item_images'];
                                                        $imageSrc = '';
                                                        
                                                        // Enhanced image URL handling logic
                                                        if (strpos($imageData, 'data:image') === 0) {
                                                            // Base64 encoded image with data URI prefix
                                                            $imageSrc = $imageData;
                                                        } else {
                                                            // Try to decode as JSON first
                                                            $decodedImages = json_decode($imageData, true);
                                                            if (is_array($decodedImages) && !empty($decodedImages)) {
                                                                $firstImage = $decodedImages[0];
                                                                
                                                                // Check if it's a full URL (starts with http)
                                                                if (strpos($firstImage, 'http') === 0) {
                                                                    $imageSrc = $firstImage;
                                                                }
                                                                // Check if it's base64 data without data URI prefix
                                                                else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                                    // It's base64 data without prefix, add the data URI prefix
                                                                    $imageSrc = 'data:image/jpeg;base64,' . $firstImage;
                                                                }
                                                                // It's a filename, construct the local URL
                                                                else {
                                                                    $imageSrc = base_url('uploads/' . $firstImage);
                                                                }
                                                            } else {
                                                                // Not JSON, check if it's a direct URL or filename
                                                                if (strpos($imageData, 'http') === 0) {
                                                                    $imageSrc = $imageData;
                                                                } else if (strpos($imageData, '/9j/') === 0 || strpos($imageData, 'iVBORw0KGgo') === 0) {
                                                                    $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                                                                } else {
                                                                    $imageSrc = base_url('uploads/' . $imageData);
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <img src="<?= $imageSrc ?>" 
                                                             alt="Found: <?= esc($claim['found_item_title']) ?>" 
                                                             class="item-thumbnail">
                                                    <?php else: ?>
                                                        <div class="item-placeholder">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                    <span class="text-xs text-gray-500 block mt-1">Found</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Match and claim info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="mb-2">
                                                <a href="<?= site_url('match/view/' . $claim['match_id']) ?>" 
                                                   class="claim-item-link group">
                                                    <span class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                                                        <?= esc($claim['lost_item_title']) ?> ↔ <?= esc($claim['found_item_title']) ?>
                                                    </span>
                                                    <svg class="inline w-3 h-3 ml-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                                <div class="mt-1">
                                                    <span class="badge bg-purple text-white small">
                                                        <?= esc($claim['lost_item_category']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="relationship-indicator">
                                                <div class="small text-muted">
                                                    <div class="flex items-center gap-1 mb-1">
                                                        <i class="bi bi-chevron-right"></i>
                                                        <span>Claim ID: #<?= $claim['id'] ?> • Match ID: #<?= $claim['match_id'] ?></span>
                                                    </div>
                                                    <div>Submitted: <?= date('M d, Y', strtotime($claim['created_at'])) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-md">
                                                <?= strtoupper(substr($claim['claimant_name'] ?? $claim['claimant_id'], 0, 1)) ?>
                                            </div>
                                            <div class="font-semibold text-gray-900 text-sm"><?= esc($claim['claimant_name'] ?? $claim['claimant_id']) ?></div>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-600 bg-gray-50 px-2 py-1.5 rounded-md">
                                            <i class="bi bi-envelope-fill mr-1.5 text-blue-500"></i>
                                            <span class="truncate"><?= esc($claim['claimant_email'] ?? $claim['claimant_id']) ?></span>
                                        </div>
                                        <?php if (!empty($claim['image'])): ?>
                                            <div class="flex items-center text-xs font-medium text-green-700 bg-green-50 px-2 py-1.5 rounded-md border border-green-200">
                                                <i class="bi bi-image-fill mr-1.5"></i>
                                                Proof Attached
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col space-y-2">
                                        <?php if ($claim['status'] === 'pending'): ?>
                                            <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                                <i class="bi bi-clock-history mr-1.5"></i>
                                                Pending Review
                                            </span>
                                        <?php elseif ($claim['status'] === 'approved'): ?>
                                            <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200 shadow-sm">
                                                <i class="bi bi-check-circle-fill mr-1.5"></i>
                                                Approved
                                            </span>
                                        <?php elseif ($claim['status'] === 'resolved'): ?>
                                            <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200 shadow-sm">
                                                <i class="bi bi-check-all mr-1.5"></i>
                                                Resolved
                                            </span>
                                        <?php elseif ($claim['status'] === 'rejected'): ?>
                                            <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200 shadow-sm">
                                                <i class="bi bi-x-circle-fill mr-1.5"></i>
                                                Denied
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">Unknown</span>
                                        <?php endif; ?>
                                        
                                        <div class="text-xs">
                                            <?php if ($claim['status'] === 'approved'): ?>
                                                <div class="flex flex-col items-center justify-center space-y-1">
                                                    <div class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md border border-blue-200">
                                                        <i class="bi bi-clock mr-1.5"></i>
                                                        <span class="font-medium">Pending Return</span>
                                                    </div>
                                                    <span class="text-xs text-gray-500 font-medium">
                                                        <i class="bi bi-calendar-event mr-1"></i><?= date('M d, Y', strtotime($claim['updated_at'])) ?>
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        <i class="bi bi-clock mr-1"></i><?= date('g:i A', strtotime($claim['updated_at'])) ?>
                                                    </span>
                                                </div>
                                            <?php elseif ($claim['status'] === 'resolved'): ?>
                                                <div class="flex flex-col items-center justify-center space-y-1">
                                                    <div class="inline-flex items-center px-2.5 py-1 bg-green-50 text-green-700 rounded-md border border-green-200">
                                                        <i class="bi bi-check-circle-fill mr-1.5"></i>
                                                        <span class="font-medium">Returned to Owner</span>
                                                    </div>
                                                    <span class="text-xs text-gray-500 font-medium">
                                                        <i class="bi bi-calendar-check mr-1"></i><?= date('M d, Y', strtotime($claim['updated_at'])) ?>
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        <i class="bi bi-clock mr-1"></i><?= date('g:i A', strtotime($claim['updated_at'])) ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-gray-500">
                                                    <div class="font-medium mb-0.5">Updated</div>
                                                    <div class="text-xs">
                                                        <i class="bi bi-calendar-event mr-1"></i><?= date('M d, Y', strtotime($claim['updated_at'])) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        <i class="bi bi-clock mr-1"></i><?= date('g:i A', strtotime($claim['updated_at'])) ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-1">
                                        <a href="<?= site_url('claims/view/' . $claim['id']) ?>" 
                                           class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" 
                                           title="View">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <?php if ($claim['status'] === 'approved'): ?>
                                        <!-- Mark as Resolved Button (only for approved claims) -->
                                        <button onclick="markAsResolved('<?= $claim['id'] ?>')" 
                                                class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-xs font-medium rounded-md hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" 
                                                title="Mark as Resolved">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <a href="<?= site_url('claims/edit/' . $claim['id']) ?>" 
                                           class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-medium rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" 
                                           title="Edit">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete('<?= $claim['id'] ?>')" 
                                                class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" 
                                                title="Delete">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 w-24 h-24">
                                            <i class="bi bi-inbox text-4xl text-blue-600"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Claims Yet</h3>
                                        <p class="text-gray-500 mb-4 text-center max-w-md">
                                            Claims will appear here when users submit ownership requests for lost items. 
                                            The system automatically matches lost and found items to generate potential claims.
                                        </p>
                                        <div class="flex flex-col space-y-2 mb-4">
                                            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                                                <i class="bi bi-info-circle"></i>
                                                <span>Claims are automatically generated when items are matched</span>
                                            </div>
                                            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                                                <i class="bi bi-clock"></i>
                                                <span>Check back regularly for new ownership requests</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 justify-center">
                                            <a href="<?= site_url('items') ?>" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                <i class="bi bi-box mr-2"></i>
                                                View All Items
                                            </a>
                                            <button onclick="window.location.reload()" 
                                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                <i class="bi bi-arrow-clockwise mr-2"></i>
                                                Refresh
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
            <!-- Mobile Card View -->
            <div class="lg:hidden">
                <div class="p-0">
                    <?php if (!empty($claims)): ?>
                        <div class="space-y-4">
                            <?php foreach($claims as $index => $claim): ?>
                                <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-xl shadow-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">#<?= $index + 1 ?></span>
                                        <div class="flex space-x-1">
                                            <a href="<?= site_url('claims/view/' . $claim['id']) ?>" 
                                               class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="View">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="<?= site_url('claims/edit/' . $claim['id']) ?>" 
                                               class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-medium rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="Edit">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <button onclick="confirmDelete('<?= $claim['id'] ?>')" 
                                                    class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="Delete">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <h6 class="card-title mb-1 text-truncate"><?= esc($claim['lost_item_title'] ?? $claim['found_item_title'] ?? 'N/A') ?></h6>
                                        <small class="text-muted"><?= esc($claim['lost_item_category'] ?? $claim['found_item_category'] ?? 'N/A') ?></small>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="bi bi-person text-muted"></i>
                                            <span class="small"><?= esc($claim['claimant_name']) ?></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-envelope text-muted"></i>
                                            <span class="small text-truncate"><?= esc($claim['claimant_email'] ?? $claim['claimant_id']) ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="badge bg-<?= $claim['status'] === 'approved' ? 'success' : ($claim['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($claim['status']) ?>
                                        </span>
                                        <small class="text-muted"><?= date('M j, Y', strtotime($claim['created_at'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-file-text display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-2">No Claims Yet</h5>
                        <p class="text-muted small mb-3">
                            Claims will appear here when users submit ownership requests for lost items.
                        </p>
                        <a href="<?= site_url('items') ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-box me-1"></i>
                            View All Items
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="bg-gray-50 border-t p-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                <div class="small text-muted">
                    Showing <?= $pager->getFirstItem() ?> to <?= $pager->getLastItem() ?> of <?= $pager->getTotal() ?> results
                </div>
                <div class="pagination-wrapper">
                    <?= $pager->links('claims', 'default_full') ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Combined filter function
        function filterClaims() {
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const headerSearch = document.getElementById('headerSearch').value.toLowerCase().trim();
            
            const rows = document.querySelectorAll('#claimsTableBody tr');
            
            rows.forEach(row => {
                // Skip empty state row
                if (row.querySelector('td').colSpan === 5) {
                    row.style.display = '';
                    return;
                }
                
                let showRow = true;
                
                // Header search filter (searches across all visible text in the row)
                if (headerSearch && showRow) {
                    const rowText = row.textContent.toLowerCase();
                    if (!rowText.includes(headerSearch)) {
                        showRow = false;
                    }
                }
                
                // Category filter
                if (categoryFilter && showRow) {
                    const categorySpan = row.querySelector('td:nth-child(2) .text-xs.text-gray-500');
                    if (categorySpan) {
                        const categoryText = categorySpan.textContent.toLowerCase().trim();
                        if (!categoryText.includes(categoryFilter.toLowerCase())) {
                            showRow = false;
                        }
                    }
                }
                
                // Status filter
                if (statusFilter && showRow) {
                    const statusBadge = row.querySelector('td:nth-child(4) .inline-flex');
                    if (statusBadge) {
                        const statusText = statusBadge.textContent.toLowerCase().trim();
                        if (!statusText.includes(statusFilter.toLowerCase())) {
                            showRow = false;
                        }
                    }
                }
                
                // Type filter (based on claim type or item type)
                if (typeFilter && showRow) {
                    const itemTitle = row.querySelector('td:nth-child(2) .claim-item-link');
                    if (itemTitle) {
                        const titleText = itemTitle.textContent.toLowerCase();
                        // Simple logic to determine type based on content
                        if (typeFilter === 'lost' && !titleText.includes('lost')) {
                            showRow = false;
                        } else if (typeFilter === 'found' && !titleText.includes('found')) {
                            showRow = false;
                        } else if (typeFilter === 'matched' && !titleText.includes('match')) {
                            showRow = false;
                        }
                    }
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
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

        // Show/hide search loading indicator
        function showSearchLoading() {
            const searchIcon = document.getElementById('searchIcon');
            const searchSpinner = document.getElementById('searchSpinner');
            if (searchIcon && searchSpinner) {
                searchIcon.classList.add('d-none');
                searchSpinner.classList.remove('d-none');
            }
        }

        function hideSearchLoading() {
            const searchIcon = document.getElementById('searchIcon');
            const searchSpinner = document.getElementById('searchSpinner');
            if (searchIcon && searchSpinner) {
                searchIcon.classList.remove('d-none');
                searchSpinner.classList.add('d-none');
            }
        }

        // Enhanced filter function with loading indicator
        function filterClaimsWithLoading() {
            showSearchLoading();
            filterClaims();
            // Hide loading after a short delay to show the filtering is complete
            setTimeout(hideSearchLoading, 300);
        }

        // Debounced search function
        const debouncedSearch = debounce(filterClaimsWithLoading, 500);

        // Event listeners
        document.getElementById('categoryFilter').addEventListener('change', filterClaimsWithLoading);
        document.getElementById('statusFilter').addEventListener('change', filterClaimsWithLoading);
        document.getElementById('typeFilter').addEventListener('change', filterClaimsWithLoading);
        
        // Enhanced search input with automatic filtering
        document.getElementById('headerSearch').addEventListener('input', function() {
            const searchValue = this.value.trim();
            if (searchValue.length > 0) {
                showSearchLoading();
            } else {
                hideSearchLoading();
            }
            debouncedSearch();
        });
    });

    function confirmDelete(claimId) {
        if (confirm('Are you sure you want to delete this claim? This action cannot be undone.')) {
            // Create a form for POST request with CSRF token
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('claims/delete/') ?>' + claimId;
            
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

    function markAsResolved(claimId) {
        if (confirm('Are you sure you want to mark this claim as resolved? This will update the status to indicate that the item has been successfully returned to its owner.')) {
            // Create a form for POST request with CSRF token
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('claims/update/') ?>' + claimId;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);
            
            // Add status field
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'resolved';
            form.appendChild(statusInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<?= $this->endSection() ?>
