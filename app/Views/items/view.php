<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Item Details - <?= esc($item['title'] ?? 'Item Details') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Modern glass morphism effects */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
    }

    /* Enhanced image styling */
    .item-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .item-image {
        transition: transform 0.4s ease;
    }
    
    .item-image:hover {
        transform: scale(1.05);
    }
    
    /* Status badges with gradients */
    .status-pending {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #8b4513;
    }
    
    .status-claimed {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #2d5016;
    }
    
    .status-default {
        background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);
        color: #4a4a4a;
    }
    
    .type-lost {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #8b1538;
    }
    
    .type-found {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .type-default {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    /* Status Badge Styles */
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
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
    
    /* Detail Item Hover Effects */
    .detail-item {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .detail-item:hover {
        background: rgba(99, 102, 241, 0.05) !important;
        border-color: rgba(99, 102, 241, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
    }
    
    /* Enhanced Action Button Animations */
    @keyframes buttonPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes iconBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    
    .action-btn:hover .animated-icon {
        animation: iconBounce 0.6s ease-in-out infinite;
    }
    
    .action-btn:active {
        animation: buttonPulse 0.3s ease;
    }
    
    /* Enhanced action buttons */
    .action-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    
    .action-btn:hover::before {
        left: 100%;
    }
    
    .edit-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.4);
    }
    
    .delete-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 20px rgba(239, 68, 68, 0.4);
    }
    
    /* Animated icons */
    .animated-icon {
        transition: transform 0.3s ease;
    }
    
    .action-btn:hover .animated-icon {
        transform: rotate(10deg) scale(1.1);
    }
    
    /* Enhanced detail items */
    .detail-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .detail-item:hover {
        background: rgba(99, 102, 241, 0.05);
        border-left-color: #6366f1;
        transform: translateX(4px);
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="glass-card rounded-2xl p-6 mb-8 border border-white/20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">
                    <?= esc($item['title'] ?? 'Item Details') ?>
                </h1>
                <p class="text-gray-600 text-lg">View and manage item information</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <?= date('M d, Y', strtotime($item['created_at'] ?? 'now')) ?>
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        ID: #<?= esc($item['id'] ?? 'N/A') ?>
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= site_url('items') ?>" 
                   class="inline-flex items-center px-6 py-3 bg-white/80 hover:bg-white text-gray-700 hover:text-gray-900 font-medium rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Single Unified Container -->
    <div class="max-w-6xl mx-auto">
        <div class="glass-card rounded-3xl border border-white/20 overflow-hidden shadow-2xl">
            <!-- Container Header -->
            <div class="bg-white p-8 border-t-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-1"><?= esc($item['title'] ?? '') ?></h2>
                            <p class="text-gray-600 text-lg">Complete Item Overview</p>
                        </div>
                    </div>
                    <div class="text-right text-gray-600">
                        <p class="text-sm">Item ID: #<?= esc($item['id'] ?? '') ?></p>
                        <p class="text-sm">Created: <?= date('M d, Y', strtotime($item['created_at'] ?? 'now')) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Grid -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Image and Description -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Image Section -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Item Image</h3>
                            </div>
                            
                            <div class="relative group cursor-pointer" onclick="openImageModal('<?= addslashes($imageSrc ?? '') ?>')">
                                <?php if (!empty($item['images'])): ?>
                                    <?php
                                    $imageData = $item['images'];
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
                                            } else {
                                                $imageSrc = base_url('uploads/' . $imageData);
                                            }
                                        }
                                    }
                                    ?>
                                    <img src="<?= esc($imageSrc) ?>" 
                                         alt="<?= esc($item['title']) ?>" 
                                         class="w-full max-h-96 object-contain rounded-xl transition-all duration-500 group-hover:scale-105 group-hover:shadow-2xl bg-white">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl flex items-center justify-center">
                                        <div class="bg-white/90 rounded-full p-3 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-full max-h-96 min-h-80 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:from-gray-200 group-hover:to-gray-300 transition-all duration-300">
                                        <div class="text-center">
                                            <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-gray-500 font-medium text-lg">No image available</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Description Section -->
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-100">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-emerald-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Description</h3>
                            </div>
                            
                            <div class="prose prose-gray max-w-none">
                                <p class="text-gray-700 leading-relaxed text-lg">
                                    <?= !empty($item['description']) ? nl2br(esc($item['description'])) : '<em class="text-gray-500">No description provided.</em>' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Details and Actions -->
                    <div class="space-y-8">
                        <!-- Item Details Section -->
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-100">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Item Details</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Category -->
                                <div class="detail-item p-4 rounded-xl bg-white/70 border border-white/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-700">Category</span>
                                        </div>
                                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                            <?= esc($item['category'] ?? 'Uncategorized') ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Status -->
                                <div class="detail-item p-4 rounded-xl bg-white/70 border border-white/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-700">Status</span>
                                        </div>
                                        <div class="text-right">
                                            <?php
                                                $status = strtolower(trim($item['status'] ?? ''));
                                                $status_class = '';
                                                $status_text = '';
                                                switch ($status) {
                                                    case 'pending':
                                                        $status_class = 'status-pending';
                                                        $status_text = 'Pending';
                                                        break;
                                                    case 'claimed':
                                                        $status_class = 'status-claimed';
                                                        $status_text = 'Successfully Claimed';
                                                        break;
                                                    case 'returned':
                                                        $status_class = 'status-returned';
                                                        $status_text = 'Case Closed';
                                                        break;
                                                    default:
                                                        $status_class = 'status-default';
                                                        $status_text = ucfirst($status);
                                                        break;
                                                }
                                            ?>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium <?= $status_class ?>">
                                                <?= esc($status_text) ?>
                                            </span>
                                            
                                            <?php if ($status === 'claimed' && isset($claims)): ?>
                                                <?php foreach ($claims as $claim): ?>
                                                    <?php if (($claim['status'] ?? '') === 'verified'): ?>
                                                        <div class="mt-2 text-sm text-green-600 flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Claimed by: <?= esc($claim['claimant_name'] ?? 'Unknown') ?>
                                                        </div>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Type -->
                                <div class="detail-item p-4 rounded-xl bg-white/70 border border-white/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM9 3v1h6V3H9zm2 5a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-700">Type</span>
                                        </div>
                                        <?php
                                            $type = strtolower(trim($item['type'] ?? ''));
                                            $type_class = '';
                                            switch ($type) {
                                                case 'lost':
                                                    $type_class = 'type-lost';
                                                    break;
                                                case 'found':
                                                    $type_class = 'type-found';
                                                    break;
                                                default:
                                                    $type_class = 'type-default';
                                                    break;
                                            }
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium <?= $type_class ?>">
                                            <?= esc(ucfirst($item['type'] ?? 'Unknown')) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Date -->
                                <div class="detail-item p-4 rounded-xl bg-white/70 border border-white/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-700">Date</span>
                                        </div>
                                        <span class="text-gray-600 font-medium">
                                            <?= date('M d, Y', strtotime($item['date_lost_found'] ?? $item['created_at'] ?? 'now')) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Contact Email -->
                                <?php if (!empty($item['reporter_email'])) : ?>
                                <div class="detail-item p-4 rounded-xl bg-white/70 border border-white/50">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <span class="font-medium text-gray-700 block">Contact Email</span>
                                            <a href="mailto:<?= esc($item['reporter_email']) ?>" 
                                               class="text-purple-600 hover:text-purple-800 transition-colors duration-200 break-all">
                                                <?= esc($item['reporter_email']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Quick Actions Section -->
                        <div class="glass-card rounded-2xl overflow-hidden p-6">
                        <div class="flex items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Quick Actions</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <a href="<?= site_url('items/edit/' . $item['id']) ?>" 
                               class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                                <svg class="animated-icon w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Item
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            
                            <a href="<?= site_url('items/delete/' . $item['id']) ?>" 
                               onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')"
                               class="action-button w-full inline-flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl transition-all duration-300 group shadow-lg hover:shadow-xl">
                                <svg class="animated-icon w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Item
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-7xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" onclick="event.stopPropagation()">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    if (imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
<?= $this->endSection() ?>
