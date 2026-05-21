<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Match Management
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
    
    .btn-action-view {
        background-color: #00B2D4;
        color: black !important;
    }
    .btn-action-edit {
        background-color: #FFC107;
        color: black !important;
    }
    .btn-action-delete {
        background-color: #E53935;
        color: white !important;
    }
    .btn-group.btn-group-sm > .btn {
        padding: .25rem .5rem;
        font-size: .875rem;
    }
    .btn-group .btn:hover {
        opacity: 0.8;
    }
    .btn-group {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: .3rem;
    }
    .btn-group .btn {
        border: none;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: .3rem;
        border-bottom-left-radius: .3rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: .3rem;
        border-bottom-right-radius: .3rem;
    }
    
    .match-score {
        font-weight: bold;
        font-size: 1.1rem;
    }
    .score-high { color: #28a745; }
    .score-medium { color: #ffc107; }
    .score-low { color: #dc3545; }
    
    /* Date filter styling */
    #dateFilter {
        min-width: 150px;
        font-size: 0.9rem;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .d-flex.gap-3.flex-wrap {
            gap: 0.5rem !important;
        }
        #dateFilter {
            min-width: 130px;
        }
    }
    
    .item-pair {
        border-left: 3px solid #0d6efd;
        padding-left: 10px;
        margin-left: 5px;
    }
    
    .match-thumbnail {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
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
    
    /* Text gradient styling */
    .text-gradient {
        background: linear-gradient(135deg, #000000 0%, #1e3a8a 50%, #581c87 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="space-y-2">
                     <h1 class="display-6 display-md-5 fw-bold text-dark mb-2 responsive-page-title">
                    <span class="text-gradient">Match Management</span>
                </h1>
                    <p class="text-lg text-gray-600 font-medium max-w-2xl">Monitor and manage lost & found matches</p>
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





        <!-- Filters Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-6">
            <form action="<?= site_url('matches') ?>" method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="statusFilter" class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="accepted" <?= $currentStatus === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                        <option value="rejected" <?= $currentStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="resolved" <?= $currentStatus === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="dateFilter" 
                           class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           value="<?= esc($currentDate ?? '') ?>">
                </div>

                <!-- Search Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i id="mainSearchIcon" class="bi bi-search text-gray-400"></i>
                            <div id="mainSearchSpinner" class="d-none">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="search" id="searchInput" placeholder="Search matches..." 
                               class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               value="<?= esc($searchQuery ?? '') ?>">
                    </div>
                </div>

            </form>
        </div>

        <!-- Match Details Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden" style="max-height: none;">
            <div class="overflow-x-auto" style="max-height: none; overflow-y: visible;">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50/80 to-blue-50/50 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Match Details</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="matchesTableBody" class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($matches)): ?>
                            <?php foreach($matches as $index => $match): ?>
                            <tr class="hover:bg-white/70 transition-colors duration-200">
                                <td class="px-3 py-2 whitespace-nowrap text-center text-sm font-medium text-gray-900"><?= $index + 1 ?></td>
                                <td class="align-middle">
                                    <div class="item-pair">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2">
                                                <?php if (!empty($match['lost_item_images'])): ?>
                                    <?php 
                                    $imageData = $match['lost_item_images'];
                                    $imageSources = [];
                                    
                                    // Enhanced image URL handling logic
                                    if (strpos($imageData, 'data:image') === 0) {
                                        // Base64 encoded image with data URI prefix
                                        $imageSources[] = $imageData;
                                    } else {
                                        // Try to decode as JSON first
                                        $decodedImages = json_decode($imageData, true);
                                        if (is_array($decodedImages) && !empty($decodedImages)) {
                                            // Process each image in the array
                                            foreach ($decodedImages as $image) {
                                                // Check if it's a full URL (starts with http)
                                                if (strpos($image, 'http') === 0) {
                                                    $imageSources[] = $image;
                                                }
                                                // Check if it's base64 data without data URI prefix
                                                else if (strpos($image, '/9j/') === 0 || strpos($image, 'iVBORw0KGgo') === 0) {
                                                    // It's base64 data without prefix, add the data URI prefix
                                                    $imageSources[] = 'data:image/jpeg;base64,' . $image;
                                                }
                                                // It's a filename, construct the local URL
                                                else {
                                                    $imageSources[] = base_url('uploads/' . $image);
                                                }
                                            }
                                        } else {
                                            // Not JSON, check if it's a direct URL or filename
                                            if (strpos($imageData, 'http') === 0) {
                                                $imageSources[] = $imageData;
                                            } else if (strpos($imageData, '/9j/') === 0 || strpos($imageData, 'iVBORw0KGgo') === 0) {
                                                $imageSources[] = 'data:image/jpeg;base64,' . $imageData;
                                            } else {
                                                $imageSources[] = base_url('uploads/' . $imageData);
                                            }
                                        }
                                    }
                                    
                                    // Display all images (limit to first 2 for space in table)
                                    $displayImages = array_slice($imageSources, 0, 2);
                                    foreach ($displayImages as $imageSrc): 
                                    ?>
                                    <img src="<?= $imageSrc ?>" 
                                         alt="Lost Item" class="match-thumbnail" style="margin-right: 5px;">
                                    <?php endforeach; ?>
                                <?php else: ?>
                                                    <div class="match-thumbnail bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong class="text-danger">Lost:</strong> <?= esc($match['lost_item_title']) ?><br>
                                                <small class="text-muted"><?= esc($match['lost_item_category']) ?> • <?= esc($match['lost_item_location']) ?></small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <?php if (!empty($match['found_item_images'])): ?>
                                    <?php 
                                    $imageData = $match['found_item_images'];
                                    $imageSources = [];
                                    
                                    // Enhanced image URL handling logic
                                    if (strpos($imageData, 'data:image') === 0) {
                                        // Base64 encoded image with data URI prefix
                                        $imageSources[] = $imageData;
                                    } else {
                                        // Try to decode as JSON first
                                        $decodedImages = json_decode($imageData, true);
                                        if (is_array($decodedImages) && !empty($decodedImages)) {
                                            // Process each image in the array
                                            foreach ($decodedImages as $image) {
                                                // Check if it's a full URL (starts with http)
                                                if (strpos($image, 'http') === 0) {
                                                    $imageSources[] = $image;
                                                }
                                                // Check if it's base64 data without data URI prefix
                                                else if (strpos($image, '/9j/') === 0 || strpos($image, 'iVBORw0KGgo') === 0) {
                                                    // It's base64 data without prefix, add the data URI prefix
                                                    $imageSources[] = 'data:image/jpeg;base64,' . $image;
                                                }
                                                // It's a filename, construct the local URL
                                                else {
                                                    $imageSources[] = base_url('uploads/' . $image);
                                                }
                                            }
                                        } else {
                                            // Not JSON, check if it's a direct URL or filename
                                            if (strpos($imageData, 'http') === 0) {
                                                $imageSources[] = $imageData;
                                            } else if (strpos($imageData, '/9j/') === 0 || strpos($imageData, 'iVBORw0KGgo') === 0) {
                                                $imageSources[] = 'data:image/jpeg;base64,' . $imageData;
                                            } else {
                                                $imageSources[] = base_url('uploads/' . $imageData);
                                            }
                                        }
                                    }
                                    
                                    // Display all images (limit to first 2 for space in table)
                                    $displayImages = array_slice($imageSources, 0, 2);
                                    foreach ($displayImages as $imageSrc): 
                                    ?>
                                    <img src="<?= $imageSrc ?>" 
                                         alt="Found Item" class="match-thumbnail" style="margin-right: 5px;">
                                    <?php endforeach; ?>
                                <?php else: ?>
                                                    <div class="match-thumbnail bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong class="text-success">Found:</strong> <?= esc($match['found_item_title']) ?><br>
                                                <small class="text-muted"><?= esc($match['found_item_category']) ?> • <?= esc($match['found_item_location']) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <?php 
                                    $score = floatval($match['score']);
                                    $scoreClass = $score >= 0.8 ? 'score-high' : ($score >= 0.5 ? 'score-medium' : 'score-low');
                                    ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                                        <?= number_format($score * 100, 0) ?>%
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="space-y-1">
                                        <?php if ($match['status'] === 'pending'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold status-pending">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pending
                                            </span>
                                        <?php elseif ($match['status'] === 'accepted'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold status-approved">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Accepted
                                            </span>
                                        <?php elseif ($match['status'] === 'resolved'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold status-resolved">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Resolved
                                            </span>
                                        <?php elseif ($match['status'] === 'rejected'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold status-rejected">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Rejected
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold status-default">Unknown</span>
                                        <?php endif; ?>
                                        
                                        <div class="text-xs text-gray-500">
                                            <?php if ($match['status'] === 'accepted' || $match['status'] === 'resolved'): ?>
                                                <div class="flex items-center justify-center text-green-600">
                                                    <svg class="w-2.5 h-2.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Match Confirmed
                                                </div>
                                            <?php else: ?>
                                                Updated: <?= date('M d', strtotime($match['updated_at'] ?? $match['created_at'])) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="text-xs text-gray-500">
                                        <?= date('M d, Y', strtotime($match['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-1">
                                        <a href="<?= site_url('match/view/' . $match['id']) ?>" 
                                           class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" 
                                           title="View">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="<?= site_url('match/edit/' . $match['id']) ?>" 
                                           class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-medium rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" 
                                           title="Edit">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete('<?= $match['id'] ?>')" 
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
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <h5>No matches found</h5>
                                        <p>There are no matches to display at the moment.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr id="clientNoMatchesRow" style="display:none">
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    <h5>No matches found</h5>
                                    <p>Try adjusting the filters or date.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> to 
                        <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> of 
                        <?= $pager->getTotal() ?> matches
                    </div>
                    <div>
                        <?= $pager->links('matches', 'default_full') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

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

function filterMatches() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    // Client-side filtering for better UX
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        let noMatchesRow = row.querySelector('.text-muted') && row.cells.length === 1;
        if (noMatchesRow) return; // Skip template no-matches row
        
        let showRow = true;
        
        // Status filter
        if (status) {
            const statusBadge = row.querySelector('.badge');
            if (!statusBadge || !statusBadge.textContent.toLowerCase().includes(status.toLowerCase())) {
                showRow = false;
            }
        }
        
        // Search filter
        if (search && showRow) {
            const rowText = row.textContent.toLowerCase();
            if (!rowText.includes(search.toLowerCase())) {
                showRow = false;
            }
        }
        
        // Date filter
        if (dateFilter && showRow) {
            const dateCell = row.cells[4]; // Date column
            if (dateCell) {
                const rowDateText = dateCell.textContent.trim();
                const dateMatch = rowDateText.match(/(\w{3} \d{1,2}, \d{4})/);
                if (dateMatch) {
                    const rowDate = new Date(dateMatch[1]);
                    const filterDate = new Date(dateFilter);
                    if (rowDate.toDateString() !== filterDate.toDateString()) {
                        showRow = false;
                    }
                }
            }
        }
        
        row.style.display = showRow ? '' : 'none';
        if (showRow) visibleCount++;
    });
    
    // Toggle "No matches found" row based on visibility
    const noMatchesRow = document.querySelector('#matchesTableBody .text-muted')?.closest('tr');
    const noMatchesClientRow = document.getElementById('clientNoMatchesRow');
    if (noMatchesRow) {
        noMatchesRow.style.display = visibleCount === 0 ? '' : 'none';
    }
    if (noMatchesClientRow) {
        noMatchesClientRow.style.display = visibleCount === 0 ? '' : 'none';
    }
    
    // Update URL for server-side filtering on page refresh
    let url = '<?= site_url('matches') ?>';
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    if (dateFilter) params.append('date', dateFilter);
    const newUrl = params.toString() ? url + '?' + params.toString() : url;
    window.history.replaceState({}, '', newUrl);
}

// Enhanced search function with loading indicators
function performSearch() {
    filterMatches();
    // Hide loading indicators after search is complete
    setTimeout(() => {
        hideSearchLoading();
    }, 300);
}

// Debounced search functions
const debouncedSearch = debounce(performSearch, 500);
const debouncedFormSubmit = debounce(() => {
    const form = document.querySelector('form[action*="matches"]');
    if (form) {
        showSearchLoading();
        form.submit();
    }
}, 500);

// Enhanced event listeners
// Auto-submit and auto-filter on change/input

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="matches"]');
    const statusSelect = document.getElementById('statusFilter');
    const dateInput = document.getElementById('dateFilter');
    const searchInputEl = document.getElementById('searchInput');

    const triggerAutoFilter = () => {
        showSearchLoading();
        filterMatches();
        // Submit for server-side filtering as well (debounced)
        if (form) {
            debouncedFormSubmit();
        }
    };

    if (statusSelect) {
        statusSelect.addEventListener('change', triggerAutoFilter);
    }
    if (dateInput) {
        dateInput.addEventListener('change', triggerAutoFilter);
    }
    if (searchInputEl) {
        searchInputEl.addEventListener('input', debouncedSearch);
    }
});

// Remove clearFilters button usage; keep function for potential programmatic resets
function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('searchInput').value = '';
    hideSearchLoading();
    filterMatches();
    const url = '<?= site_url('matches') ?>';
    window.history.replaceState({}, '', url);
}

function confirmDelete(matchId) {
    if (confirm('Are you sure you want to delete this match? This action cannot be undone.')) {
        // Create a form for POST request with CSRF token
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('match/delete/') ?>' + matchId;
        
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

</script>

<?= $this->endSection() ?>