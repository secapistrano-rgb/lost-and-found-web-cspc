<?php
// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
CSPC Lost & Found - Feedback Management
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Modern Glassmorphism Design - Updated 2024 */
    .glass-card {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-button {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.1); }
        50% { box-shadow: 0 0 30px rgba(255, 255, 255, 0.2); }
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-glow {
        animation: glow 3s ease-in-out infinite;
    }
    
    .animate-shimmer {
        animation: shimmer 2s ease-in-out infinite;
    }
    
    .feedback-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    
    .feedback-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    .feedback-card::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         width: 4px;
         height: 100%;
         background: linear-gradient(135deg, #a8c8ec 0%, #d4a5d8 100%);
     }
    
    .rating-stars {
        color: #ffc107;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-responded {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-resolved {
        background: #d4edda;
        color: #155724;
    }
    
    .feedback-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .stat-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 2rem;
        text-align: center;
        transition: box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        min-height: 160px;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    /* Subtle card styling with left borders */
    .stat-card.card-total {
        background: #f8f9ff;
        border-left: 4px solid #667eea;
        color: #1f2937;
    }
    
    .stat-card.card-pending {
        background: #fff7ed;
        border-left: 4px solid #f97316;
        color: #1f2937;
    }
    
    .stat-card.card-responded {
        background: #f0fdf4;
        border-left: 4px solid #22c55e;
        color: #1f2937;
    }
    
    .stat-card.card-rating {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #1f2937;
    }
    
    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .stat-card .stat-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.7;
    }
    
    .stat-card.card-total .stat-icon { color: #667eea; }
    .stat-card.card-pending .stat-icon { color: #f97316; }
    .stat-card.card-responded .stat-icon { color: #22c55e; }
    .stat-card.card-rating .stat-icon { color: #ef4444; }
    
    .stat-count {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
        color: #1f2937;
    }
    
    .filter-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .filter-card h5 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mg-page mg-feedback">
<div class="container-fluid feedback-page">
    <!-- Modern Header Section -->
    <div class="mg-page-header glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
            <div class="space-y-3">
                <p class="mg-eyebrow">COMMUNITY SUPPORT</p>
                    <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Feedback Management</h1>
                <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Review suggestions, respond to concerns, and close the loop.</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-caption text-xs">Live Updates</span>
                    </div>
                    <div class="text-caption text-xs">Last Updated: <?= date('M d, Y \a\t g:i A') ?></div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="lg:w-auto"></div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="mg-stat-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Feedback Card -->
        <div class="mg-stat-card bg-gradient-to-br from-blue-50/80 to-blue-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 0.5s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 mb-1">Total Feedback</p>
                    <p class="text-2xl font-bold text-blue-900"><?= $stats['total'] ?? 0 ?></p>
                    <p class="text-xs text-blue-600">All submissions</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-chat-square-text-fill text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Card -->
        <div class="mg-stat-card bg-gradient-to-br from-amber-50/80 to-amber-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 via-amber-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-700 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-amber-900"><?= $stats['pending'] ?? 0 ?></p>
                    <p class="text-xs text-amber-600">Awaiting review</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-hourglass-split text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Addressed Card -->
        <div class="mg-stat-card bg-gradient-to-br from-green-50/80 to-green-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1.5s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 via-green-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700 mb-1">Addressed</p>
                    <p class="text-2xl font-bold text-green-900"><?= $stats['addressed'] ?? 0 ?></p>
                    <p class="text-xs text-green-600">Completed</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-check2-square text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Rating Card -->
        <div class="mg-stat-card bg-gradient-to-br from-purple-50/80 to-purple-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 2s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 via-purple-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700 mb-1">Average Rating</p>
                    <p class="text-2xl font-bold text-purple-900"><?= number_format($stats['average_rating'] ?? 0, 1) ?></p>
                    <p class="text-xs text-purple-600">Out of 5 stars</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-star-fill text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card filter-card mg-filter-panel">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-funnel me-2"></i>Filter Feedback</h5>
            <form method="GET" action="<?= site_url('feedback') ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" <?= (request()->getGet('status') == 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="reviewed" <?= (request()->getGet('status') == 'reviewed') ? 'selected' : '' ?>>Reviewed</option>
                            <option value="addressed" <?= (request()->getGet('status') == 'addressed') ? 'selected' : '' ?>>Addressed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label fw-semibold">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="general" <?= (request()->getGet('type') == 'general') ? 'selected' : '' ?>>General</option>
                            <option value="bug_report" <?= (request()->getGet('type') == 'bug_report') ? 'selected' : '' ?>>Bug Report</option>
                            <option value="feature_request" <?= (request()->getGet('type') == 'feature_request') ? 'selected' : '' ?>>Feature Request</option>
                            <option value="complaint" <?= (request()->getGet('type') == 'complaint') ? 'selected' : '' ?>>Complaint</option>
                            <option value="suggestion" <?= (request()->getGet('type') == 'suggestion') ? 'selected' : '' ?>>Suggestion</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rating" class="form-label fw-semibold">Rating</label>
                        <select name="rating" id="rating" class="form-select">
                            <option value="">All Ratings</option>
                            <option value="5" <?= (request()->getGet('rating') == '5') ? 'selected' : '' ?>>5 Stars</option>
                            <option value="4" <?= (request()->getGet('rating') == '4') ? 'selected' : '' ?>>4 Stars</option>
                            <option value="3" <?= (request()->getGet('rating') == '3') ? 'selected' : '' ?>>3 Stars</option>
                            <option value="2" <?= (request()->getGet('rating') == '2') ? 'selected' : '' ?>>2 Stars</option>
                            <option value="1" <?= (request()->getGet('rating') == '1') ? 'selected' : '' ?>>1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-semibold">Search</label>
                        <div class="position-relative">
                            <div class="position-absolute top-50 start-0 translate-middle-y ms-3">
                                <i id="searchIcon" class="bi bi-search text-muted"></i>
                                <div id="searchSpinner" class="d-none">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <input type="text" name="search" id="search" class="form-control ps-5" 
                                   placeholder="Search Feedback..." value="<?= esc(request()->getGet('search')) ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern Feedback List -->
    <div class="glass-card bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/30 overflow-hidden" style="max-height: none;">
        <div class="overflow-x-auto" style="max-height: none; overflow-y: visible;">
            <div class="mg-table-container"><table class="mg-table w-full" id="feedbackTable">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50/80 to-blue-50/50 backdrop-blur-sm">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Feedback</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Type & Rating</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-gray-100/50">
                    <?php if (!empty($feedback)): ?>
                        <?php foreach ($feedback as $item): ?>
                            <tr class="hover:bg-white/70 transition-all duration-200 backdrop-blur-sm">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <div class="text-sm font-medium text-gray-900 mb-1"><?= ucfirst(str_replace('_', ' ', $item['type'])) ?> Feedback</div>
                                        <div class="text-sm text-gray-500 truncate" title="<?= esc($item['text']) ?>">
                                            <?= esc(substr($item['text'], 0, 100)) ?><?= strlen($item['text']) > 100 ? '...' : '' ?>
                                        </div>
                                        <?php if (!empty($item['responses'])): ?>
                                            <div class="mt-2 text-xs text-blue-600">
                                                <i class="bi bi-reply-fill mr-1"></i>Has Response
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="bi bi-tag mr-1"></i>
                                            <?= ucfirst(str_replace('_', ' ', $item['type'])) ?>
                                        </span>
                                        <?php if ($item['rating']): ?>
                                            <div class="flex items-center">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi bi-star<?= $i <= $item['rating'] ? '-fill' : '' ?> text-yellow-400 text-xs"></i>
                                                <?php endfor; ?>
                                                <span class="ml-1 text-xs text-gray-500">(<?= $item['rating'] ?>/5)</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'reviewed' => 'bg-blue-100 text-blue-800', 
                                        'addressed' => 'bg-green-100 text-green-800'
                                    ];
                                    $statusColor = $statusColors[$item['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusColor ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3 overflow-hidden">
                                            <?php if (!empty($item['user_photo_url'])): ?>
                                                <img src="<?= esc($item['user_photo_url']) ?>" 
                                                     alt="<?= esc($item['user_name']) ?>" 
                                                     class="w-full h-full object-cover rounded-full"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="w-full h-full bg-gray-100 rounded-full flex items-center justify-center" style="display: none;">
                                                    <i class="bi bi-person text-gray-500 text-sm"></i>
                                                </div>
                                            <?php else: ?>
                                                <i class="bi bi-person text-gray-500 text-sm"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= esc($item['user_name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= esc($item['user_email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= date('M d, Y', strtotime($item['created_at'])) ?></div>
                                    <div class="text-sm text-gray-500"><?= date('g:i A', strtotime($item['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= site_url('feedback/view/' . $item['id']) ?>" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="View Details">
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <?php if ($item['status'] != 'resolved'): ?>
                                            <a href="<?= site_url('feedback/respond/' . $item['id']) ?>" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-medium rounded-md hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" title="Respond">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="deleteFeedback('<?= $item['id'] ?>')" title="Delete">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="bi bi-chat-square-text text-gray-400 text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No feedback found</h3>
                                        <p class="text-gray-500">No feedback matches your current filters. Try adjusting your search criteria.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table></div>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="bg-white px-6 py-3 border-t border-gray-200/50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <?= $pager->getFirstItem() ?> to <?= $pager->getLastItem() ?> of <?= $pager->getTotal() ?> results
                </div>
                <div class="pagination-wrapper">
                    <?= $pager->links('feedback', 'default_full') ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this feedback? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for status updates -->
<form id="statusForm" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="status" id="statusInput">
</form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function deleteFeedback(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `<?= site_url('feedback/delete/') ?>${id}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function updateStatus(id, status) {
        console.log('updateStatus called with id:', id, 'status:', status);
        
        const statusForm = document.getElementById('statusForm');
        const statusInput = document.getElementById('statusInput');
        
        if (!statusForm || !statusInput) {
            console.error('Status form or input not found');
            return;
        }
        
        statusForm.action = `<?= site_url('feedback/update_status/') ?>${id}`;
        statusInput.value = status;
        
        console.log('Form action set to:', statusForm.action);
        console.log('Status input value set to:', statusInput.value);
        
        statusForm.submit();
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

// Show/hide search loading indicators
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

// Automatic search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('type');
    const ratingFilter = document.getElementById('rating');
    const feedbackTable = document.getElementById('feedbackTable');

    function performFilter() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedType = typeFilter.value;
        const selectedRating = ratingFilter.value;
        
        if (feedbackTable) {
            const rows = feedbackTable.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                // Skip empty state rows
                if (!row.querySelector('td') || row.querySelector('td').colSpan > 1) {
                    row.style.display = '';
                    return;
                }
                
                let showRow = true;
                
                // Search filter
                if (searchTerm && showRow) {
                    const rowText = row.textContent.toLowerCase();
                    if (!rowText.includes(searchTerm)) {
                        showRow = false;
                    }
                }
                
                // Type filter
                if (selectedType && showRow) {
                    const typeCell = row.querySelector('td:nth-child(2)'); // Type & Rating column
                    if (typeCell) {
                        const typeText = typeCell.textContent.toLowerCase();
                        if (!typeText.includes(selectedType.toLowerCase())) {
                            showRow = false;
                        }
                    }
                }
                
                // Rating filter
                if (selectedRating && showRow) {
                    const ratingCell = row.querySelector('td:nth-child(2)'); // Type & Rating column
                    if (ratingCell) {
                        const stars = ratingCell.querySelectorAll('.text-warning');
                        const starCount = stars.length;
                        if (starCount.toString() !== selectedRating) {
                            showRow = false;
                        }
                    }
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        // Hide loading after filter is complete
        setTimeout(() => hideSearchLoading(), 300);
    }

    // Debounced search function
    const debouncedFilter = debounce(performFilter, 500);
    const debouncedFormSubmit = debounce(() => {
        const form = document.querySelector('form[method="get"]');
        if (form) {
            showSearchLoading();
            form.submit();
        }
    }, 500);

    // Enhanced search with loading indicators
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.trim();
            if (searchValue.length > 0) {
                showSearchLoading();
            } else {
                hideSearchLoading();
            }
            debouncedFilter();
        });
    }

    // Immediate filter on dropdown changes
    if (typeFilter) {
        typeFilter.addEventListener('change', performFilter);
    }

    if (ratingFilter) {
        ratingFilter.addEventListener('change', performFilter);
    }
});
</script>
<?= $this->endSection() ?>