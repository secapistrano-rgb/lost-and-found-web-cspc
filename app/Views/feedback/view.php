<?php
// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Feedback Details - <?= esc($feedback['type']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Modern Glassmorphism Design */
    /* .glass-card {
        background: rgba(    <div class="max-w-7xl mx-auto">

        <!-- Feedback Details Card -->95) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative;
        overflow: hidden;
    } */
    
    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .rating-stars {
        color: #fbbf24;
        font-size: 1.2rem;
        filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.3));
    }
    
    /* Enhanced Status Badges with Gradients */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .status-addressed {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }
    
    .status-resolved {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-in_progress {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .status-closed {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    /* Enhanced Content Sections */
    .feedback-content {
        background: rgba(248, 250, 252, 0.8);
        border: 1px solid rgba(226, 232, 240, 0.5);
        border-radius: 12px;
        padding: 2.5rem;
        margin-bottom: 3rem;
        backdrop-filter: blur(10px);
    }
    
    .feedback-content .prose p {
        margin-bottom: 1rem;
        line-height: 1.8;
        font-size: 1.1rem;
        word-spacing: 0.1em;
        letter-spacing: 0.02em;
    }
    
    .feedback-content h3 {
        margin-bottom: 1.5rem;
    }
    
    .response-section {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 197, 253, 0.1));
        border-radius: 12px;
        padding: 2.5rem;
        margin-top: 3rem;
        margin-bottom: 4rem;
        border-left: 4px solid #3b82f6;
        backdrop-filter: blur(10px);
    }
    
    .response-section .prose p {
        margin-bottom: 1rem;
        line-height: 1.8;
        font-size: 1.05rem;
        word-spacing: 0.1em;
        letter-spacing: 0.02em;
    }
    
    .response-section h3 {
        margin-bottom: 1.5rem;
    }
    
    .response-section .glass-card {
        padding: 1.5rem !important;
        margin-bottom: 1rem;
    }
    
    .response-section .glass-card .prose p {
        margin-bottom: 1rem;
        line-height: 1.7;
        font-size: 1rem;
        word-spacing: 0.05em;
    }
    
    .response-section .space-y-4 > * + * {
        margin-top: 1.5rem;
    }
    
    .user-info-card {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-glass {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .btn-glass:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: rgba(255, 255, 255, 0.95);
    }
    
    /* Profile Image Enhancement */
    .profile-image {
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    
    .profile-image:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }
    
    /* High specificity background override */
    html, body, .main-content, .container-fluid, .content-wrapper {
        background: #f8fafc !important;
        background-image: none !important;
    }
    
    .gradient-bg {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
        position: relative;
        overflow: hidden;
        min-height: 120px;
    }
    
    .gradient-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    }
    
    .gradient-bg > * {
        position: relative;
        z-index: 2;
    }
    
    /* Enhanced Typography */
    .text-gradient {
        background: linear-gradient(135deg, #1f2937 0%, #6b21a8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
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
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: #f8fafc !important;">
    <!-- Header Section -->
    <div class="glass-card mb-8 rounded-2xl overflow-hidden">
        <div class="h-1 bg-blue-600"></div>
        <div class="gradient-bg p-6 rounded-2xl">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-2">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 tracking-tight mb-1">
                        Feedback Details
                    </h1>
                    <p class="text-gray-600 text-lg">
                        Comprehensive feedback information and management
                    </p>
                    <nav class="text-gray-500 text-sm" aria-label="breadcrumb">
                        <span><a href="<?= site_url('feedback') ?>" class="hover:text-gray-800 transition-colors">Feedback Management</a></span>
                        <span class="mx-2">›</span>
                        <span class="text-gray-700">View Details</span>
                    </nav>
                </div>
                <div class="text-right">
                    <div class="text-gray-600 text-sm space-x-4">
                        <span class="inline-flex items-center">
                            <i class="bi bi-circle-fill w-4 h-4 mr-1 text-green-400"></i>
                            Live Updates
                        </span>
                        <span class="inline-flex items-center">
                            <i class="bi bi-clock w-5 h-5 mr-2"></i>
                            <span id="current-time"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto">

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Feedback Details Section -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Main Feedback Card -->
                <div class="glass-card p-8 mb-10">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h2 class="text-2xl font-bold text-gradient">
                            <?= ucfirst(str_replace('_', ' ', $feedback['type'])) ?> Feedback
                        </h2>
                        <span class="status-badge status-<?= $feedback['status'] ?>">
                            <?= ucfirst($feedback['status']) ?>
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Type</label>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800">
                                    <i class="bi bi-tag mr-2"></i>
                                    <?= ucfirst(str_replace('_', ' ', $feedback['type'])) ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($feedback['rating']): ?>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Rating</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= $feedback['rating'] ? '-fill' : '' ?> rating-stars"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-lg font-semibold text-gray-700">(<?= $feedback['rating'] ?>/5)</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="feedback-content">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="bi bi-chat-text text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Feedback Content</h3>
                        </div>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed"><?= nl2br(esc($feedback['text'])) ?></p>
                        </div>
                    </div>
                    
                    <?php if (!empty($feedback['responses'])): ?>
                        <div class="response-section">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <i class="bi bi-reply text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Admin Responses</h3>
                            </div>
                            <div class="space-y-4">
                                <?php foreach ($feedback['responses'] as $response): ?>
                                    <div class="glass-card p-4 border-l-4 border-blue-500">
                                        <div class="prose max-w-none mb-3">
                                            <p class="text-gray-700 leading-relaxed"><?= nl2br(esc($response['response_text'])) ?></p>
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <i class="bi bi-person mr-1"></i>
                                                <?= esc($response['admin_name'] ?: $response['admin_email']) ?>
                                            </span>
                                            <span class="flex items-center">
                                                <i class="bi bi-clock mr-1"></i>
                                                <?= date('M d, Y g:i A', strtotime($response['created_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- User Information Card -->
                <div class="glass-card p-6">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <i class="bi bi-person text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">User Information</h3>
                    </div>
                    
                    <?php if ($feedback['user_photo_url']): ?>
                        <div class="text-center mb-6">
                            <img src="<?= esc($feedback['user_photo_url']) ?>" 
                                 alt="User Photo" 
                                 class="profile-image rounded-full mx-auto" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="space-y-4">
                        <div class="user-info-card p-4">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Name</label>
                            <p class="text-gray-800 font-medium"><?= esc($feedback['user_name'] ?: 'Anonymous') ?></p>
                        </div>
                        
                        <div class="user-info-card p-4">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Email</label>
                            <a href="mailto:<?= esc($feedback['user_email']) ?>" 
                               class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                <?= esc($feedback['user_email']) ?>
                            </a>
                        </div>
                        
                        <div class="user-info-card p-4">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Submitted</label>
                            <p class="text-gray-800 font-medium"><?= date('M d, Y g:i A', strtotime($feedback['created_at'])) ?></p>
                        </div>
                        
                        <?php if ($feedback['updated_at'] != $feedback['created_at']): ?>
                        <div class="user-info-card p-4">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Last Updated</label>
                            <p class="text-gray-800 font-medium"><?= date('M d, Y g:i A', strtotime($feedback['updated_at'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Actions Card -->
                <div class="glass-card p-6">
                    <div class="flex items-center space-x-2 mb-6">
                        <!-- <div class="p-2 bg-yellow-500 rounded-lg">
                            <i class="bi bi-lightning text-white"></i>
                        </div> -->
                        <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="<?= site_url('feedback/respond/' . $feedback['id']) ?>" 
                           class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                            <i class="bi bi-reply animated-icon w-5 h-5 mr-2"></i>
                            Respond
                        </a>
                        
                        <button type="button" 
                                class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl" 
                                onclick="deleteFeedback('<?= $feedback['id'] ?>')">
                            <i class="bi bi-trash animated-icon w-5 h-5 mr-2"></i>
                            Delete
                        </button>
                        
                        <a href="<?= site_url('feedback') ?>" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 btn-glass text-gray-700 font-medium rounded-lg">
                            <i class="bi bi-arrow-left mr-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-red-500 rounded-lg">
                        <i class="bi bi-exclamation-triangle text-white"></i>
                    </div>
                    <h5 class="modal-title text-xl font-semibold text-gray-800">Confirm Delete</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <div class="space-y-4">
                    <p class="text-gray-700">Are you sure you want to delete this feedback?</p>
                    <div class="user-info-card p-4">
                        <div class="space-y-2">
                            <div><strong class="text-gray-600">Type:</strong> <span class="text-gray-800"><?= esc(ucfirst(str_replace('_', ' ', $feedback['type']))) ?></span></div>
                            <div><strong class="text-gray-600">From:</strong> <span class="text-gray-800"><?= esc($feedback['user_name']) ?></span></div>
                        </div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-800 font-semibold flex items-center">
                            <i class="bi bi-exclamation-triangle mr-2"></i>
                            This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-glass px-6 py-2 rounded-lg" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300">
                        Delete Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Enhanced delete feedback function
function deleteFeedback(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `<?= site_url('feedback/delete/') ?>${id}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Real-time clock update
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

// Update time immediately and then every second
updateTime();
setInterval(updateTime, 1000);

// Enhanced hover effects for cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.glass-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
<?= $this->endSection() ?>