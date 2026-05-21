<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Claim Details
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
        padding: 2rem;
    }

    /* Blue top accent for cards when needed */
    .header-accent {
        border-top: 4px solid #3b82f6; /* blue-600 */
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .sticky-status-card {
        position: sticky;
        top: 2rem;
        z-index: 10;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }
    
    /* Smooth scrolling for the sticky card */
    .sticky-status-card::-webkit-scrollbar {
        width: 4px;
    }
    
    .sticky-status-card::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
    }
    
    .sticky-status-card::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.3);
        border-radius: 2px;
    }
    
    .sticky-status-card::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.5);
    }

    /* Quick Action button styling (unified) */
    .action-button {
        background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border-radius: 0.75rem; /* rounded-xl */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .action-button::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0) 60%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    .action-button:hover::before {
        transform: translateX(0);
    }
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.25);
    }
    .animated-icon {
        transition: transform 0.3s ease;
    }
    .action-button:hover .animated-icon {
        transform: translateX(3px);
    }
</style>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="glass-card header-accent mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">
                    Claim Details
                </h1>
                <p class="text-gray-600">Review and manage claim information</p>
            </div>
            <div class="flex gap-3">
                <a href="<?= site_url('claims') ?>" 
                   class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-300 shadow-sm hover:shadow-md">
                    <i class="bi bi-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Claim and Item Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="bi bi-link-45deg mr-3 text-blue-500"></i>Match Details
                    </h2>
                    <p class="text-gray-600 mt-2">Comparison between the lost and found items</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <th class="text-left py-4 px-6 font-semibold text-gray-700 border-b border-gray-200 rounded-tl-xl">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                        Lost Item
                                    </div>
                                </th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700 border-b border-gray-200 rounded-tr-xl">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                        Found Item
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Images Row -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 px-6">
                                    <?php if (!empty($claim['lost_item_images'])): ?>
                                        <?php 
                                        $lostImageData = $claim['lost_item_images'];
                                        $lostImageSrc = '';
                                        
                                        // Enhanced image URL handling logic
                                        if (strpos($lostImageData, 'data:image') === 0) {
                                            // Base64 encoded image with data URI prefix
                                            $lostImageSrc = $lostImageData;
                                        } else {
                                            // Try to decode as JSON first
                                            $decodedImages = json_decode($lostImageData, true);
                                            if (is_array($decodedImages) && !empty($decodedImages)) {
                                                $firstImage = $decodedImages[0];
                                                
                                                // Check if it's a full URL (starts with http)
                                                if (strpos($firstImage, 'http') === 0) {
                                                    $lostImageSrc = $firstImage;
                                                }
                                                // Check if it's base64 data without data URI prefix
                                                else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                    // It's base64 data without prefix, add the data URI prefix
                                                    $lostImageSrc = 'data:image/jpeg;base64,' . $firstImage;
                                                }
                                                // It's a filename, construct the local URL
                                                else {
                                                    $lostImageSrc = base_url('uploads/' . $firstImage);
                                                }
                                            } else {
                                                // Not JSON, check if it's a direct URL or filename
                                                if (strpos($lostImageData, 'http') === 0) {
                                                    $lostImageSrc = $lostImageData;
                                                } else if (strpos($lostImageData, '/9j/') === 0 || strpos($lostImageData, 'iVBORw0KGgo') === 0) {
                                                    $lostImageSrc = 'data:image/jpeg;base64,' . $lostImageData;
                                                } else {
                                                    $lostImageSrc = base_url('uploads/' . $lostImageData);
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="relative group">
                                            <div class="aspect-video bg-white rounded-xl overflow-hidden shadow-lg border border-red-200 max-w-sm">
                                                <img src="<?= $lostImageSrc ?>" 
                                                     alt="<?= esc($claim['lost_item_title']) ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="aspect-video bg-gray-100 rounded-xl flex items-center justify-center max-w-sm">
                                            <i class="bi bi-image text-gray-400 text-3xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-6 px-6">
                                    <?php if (!empty($claim['found_item_images'])): ?>
                                        <?php 
                                        $foundImageData = $claim['found_item_images'];
                                        $foundImageSrc = '';
                                        
                                        // Enhanced image URL handling logic
                                        if (strpos($foundImageData, 'data:image') === 0) {
                                            // Base64 encoded image with data URI prefix
                                            $foundImageSrc = $foundImageData;
                                        } else {
                                            // Try to decode as JSON first
                                            $decodedImages = json_decode($foundImageData, true);
                                            if (is_array($decodedImages) && !empty($decodedImages)) {
                                                $firstImage = $decodedImages[0];
                                                
                                                // Check if it's a full URL (starts with http)
                                                if (strpos($firstImage, 'http') === 0) {
                                                    $foundImageSrc = $firstImage;
                                                }
                                                // Check if it's base64 data without data URI prefix
                                                else if (strpos($firstImage, '/9j/') === 0 || strpos($firstImage, 'iVBORw0KGgo') === 0) {
                                                    // It's base64 data without prefix, add the data URI prefix
                                                    $foundImageSrc = 'data:image/jpeg;base64,' . $firstImage;
                                                }
                                                // It's a filename, construct the local URL
                                                else {
                                                    $foundImageSrc = base_url('uploads/' . $firstImage);
                                                }
                                            } else {
                                                // Not JSON, check if it's a direct URL or filename
                                                if (strpos($foundImageData, 'http') === 0) {
                                                    $foundImageSrc = $foundImageData;
                                                } else if (strpos($foundImageData, '/9j/') === 0 || strpos($foundImageData, 'iVBORw0KGgo') === 0) {
                                                    $foundImageSrc = 'data:image/jpeg;base64,' . $foundImageData;
                                                } else {
                                                    $foundImageSrc = base_url('uploads/' . $foundImageData);
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="relative group">
                                            <div class="aspect-video bg-white rounded-xl overflow-hidden shadow-lg border border-green-200 max-w-sm">
                                                <img src="<?= $foundImageSrc ?>" 
                                                     alt="<?= esc($claim['found_item_title']) ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="aspect-video bg-gray-100 rounded-xl flex items-center justify-center max-w-sm">
                                            <i class="bi bi-image text-gray-400 text-3xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <!-- Title & Category Row -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="space-y-2">
                                        <h5 class="font-semibold text-gray-800 text-lg"><?= esc($claim['lost_item_title']) ?></h5>
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full">
                                            <?= esc($claim['lost_item_category']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="space-y-2">
                                        <h5 class="font-semibold text-gray-800 text-lg"><?= esc($claim['found_item_title']) ?></h5>
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full">
                                            <?= esc($claim['found_item_category']) ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Description Row -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="text-gray-600 leading-relaxed"><?= esc($claim['lost_item_description']) ?></p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-gray-600 leading-relaxed"><?= esc($claim['found_item_description']) ?></p>
                                </td>
                            </tr>
                            
                            <!-- Location Row -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-geo-alt-fill text-red-500 mr-2"></i>
                                        <span class="font-medium mr-2">Location:</span>
                                        <span><?= esc($claim['lost_item_location'] ?? 'Not specified') ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-geo-alt-fill text-green-500 mr-2"></i>
                                        <span class="font-medium mr-2">Location:</span>
                                        <span><?= esc($claim['found_item_location'] ?? 'Not specified') ?></span>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Date Row -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-calendar-event text-red-500 mr-2"></i>
                                        <span class="font-medium mr-2">Date Lost:</span>
                                        <span><?= $claim['lost_item_date_lost_found'] ? date('M j, Y', strtotime($claim['lost_item_date_lost_found'])) : 'Not specified' ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-calendar-event text-green-500 mr-2"></i>
                                        <span class="font-medium mr-2">Date Found:</span>
                                        <span><?= $claim['found_item_date_lost_found'] ? date('M j, Y', strtotime($claim['found_item_date_lost_found'])) : 'Not specified' ?></span>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Reporter Row -->
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 rounded-bl-xl">
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-person-fill text-red-500 mr-2"></i>
                                        <span class="font-medium mr-2">Reported by:</span>
                                        <span><?= esc($claim['lost_item_reporter_name'] ?? 'Not specified') ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 rounded-br-xl">
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-person-fill text-green-500 mr-2"></i>
                                        <span class="font-medium mr-2">Reported by:</span>
                                        <span><?= esc($claim['found_item_reporter_name'] ?? 'Not specified') ?></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Additional Match Information -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4 rounded-t-xl">
                    <h5 class="text-lg font-semibold flex items-center mb-0">
                        <i class="bi bi-info-circle mr-2"></i>Match Information
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-arrow-left-right mr-2"></i>Match Details
                            </h6>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Match ID</span>
                                    <span class="text-gray-900 font-mono">#<?= esc($claim['match_id']) ?></span>
                                </div>
                                
                                <div class="py-2 border-b border-gray-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-gray-700">Confidence Score</span>
                                        <span class="text-gray-900 font-semibold"><?= number_format(($claim['score'] ?? 0) * 100, 0) ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <?php 
                                        $score = ($claim['score'] ?? 0) * 100;
                                        $progressColor = $score >= 80 ? 'bg-gradient-to-r from-green-400 to-green-600' : ($score >= 60 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : 'bg-gradient-to-r from-red-400 to-red-600');
                                        ?>
                                        <div class="<?= $progressColor ?> h-3 rounded-full transition-all duration-500" 
                                             style="width: <?= $score ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium text-gray-700">Match Status</span>
                                    <div>
                                        <?php
                                        $matchStatusClass = '';
                                        switch ($claim['match_status']) {
                                            case 'accepted':
                                            case 'resolved':
                                                $matchStatusClass = 'bg-green-100 text-green-800';
                                                break;
                                            case 'rejected':
                                                $matchStatusClass = 'bg-red-100 text-red-800';
                                                break;
                                            case 'pending':
                                            default:
                                                $matchStatusClass = 'bg-yellow-100 text-yellow-800';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $matchStatusClass ?>">
                                            <?= ucfirst(esc($claim['match_status'] ?? 'Unknown')) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h6 class="text-indigo-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-clock-history mr-2"></i>Timeline
                            </h6>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg border border-red-100">
                                    <i class="bi bi-calendar-x text-red-500 text-lg mt-0.5"></i>
                                    <div>
                                        <div class="font-medium text-red-800">Lost</div>
                                        <div class="text-red-600 text-sm">
                                            <?= $claim['lost_item_date_lost_found'] ? date('M j, Y', strtotime($claim['lost_item_date_lost_found'])) : 'Date not specified' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                                    <i class="bi bi-calendar-check text-green-500 text-lg mt-0.5"></i>
                                    <div>
                                        <div class="font-medium text-green-800">Found</div>
                                        <div class="text-green-600 text-sm">
                                            <?= $claim['found_item_date_lost_found'] ? date('M j, Y', strtotime($claim['found_item_date_lost_found'])) : 'Date not specified' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <i class="bi bi-file-earmark-text text-blue-500 text-lg mt-0.5"></i>
                                    <div>
                                        <div class="font-medium text-blue-800">Claimed</div>
                                        <div class="text-blue-600 text-sm">
                                            <?= date('M j, Y', strtotime($claim['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claimant Information -->
            <div class="glass-card">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="bi bi-person-check mr-3 text-purple-500"></i>Claimant Information
                    </h2>
                    <p class="text-gray-600 mt-2">Details about the person claiming this item</p>
                </div>
                
                <div class="space-y-6">
                    <!-- Basic Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Claimant Name</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium"><?= esc($claim['claimant_name'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Email Address</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-900 font-medium"><?= esc($claim['claimant_email'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submission Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Submitted Date</label>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="bi bi-calendar-event text-blue-500 mr-2"></i>
                                <span class="text-gray-900 font-medium"><?= date('F j, Y, g:i a', strtotime($claim['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Claimed Date - Only show when status is resolved (not approved) -->
                    <?php if (isset($claim['status']) && strtolower($claim['status']) === 'resolved' && !empty($claim['updated_at'])): ?>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Claimed Date</label>
                        <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <i class="bi bi-calendar-check text-green-500 mr-2"></i>
                                <span class="text-gray-900 font-medium"><?= date('F j, Y, g:i a', strtotime($claim['updated_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Proof of Ownership -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Proof of Ownership</label>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-900 leading-relaxed whitespace-pre-line"><?= esc($claim['proof_of_ownership'] ?? 'No proof provided') ?></p>
                        </div>
                    </div>
                    
                    <!-- Proof Image -->
                    <?php if (!empty($claim['image'])): ?>
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <i class="bi bi-image mr-2"></i>
                                <h5 class="mb-0">Proof Image</h5>
                            </div>
                                <?php 
                                $proofImageData = $claim['image'];
                                $proofImageSrc = '';
                                
                                // Enhanced image URL handling logic
                                if (strpos($proofImageData, 'data:image') === 0) {
                                    // Base64 encoded image with data URI prefix
                                    $proofImageSrc = $proofImageData;
                                } else {
                                    // Try to decode as JSON first
                                    $decodedProofImage = json_decode($proofImageData, true);
                                    if (is_array($decodedProofImage) && !empty($decodedProofImage)) {
                                        // Handle mobile app format with uri, fileName, type
                                        if (isset($decodedProofImage['uri']) && isset($decodedProofImage['fileName'])) {
                                            $proofImageSrc = base_url('uploads/' . $decodedProofImage['fileName']);
                                        } else {
                                            $firstProofImage = $decodedProofImage[0];
                                            
                                            // Check if it's a full URL (starts with http)
                                            if (strpos($firstProofImage, 'http') === 0) {
                                                $proofImageSrc = $firstProofImage;
                                            }
                                            // Check if it's base64 data without data URI prefix
                                            else if (strpos($firstProofImage, '/9j/') === 0 || strpos($firstProofImage, 'iVBORw0KGgo') === 0) {
                                                // It's base64 data without prefix, add the data URI prefix
                                                $proofImageSrc = 'data:image/jpeg;base64,' . $firstProofImage;
                                            }
                                            // It's a filename, construct the local URL
                                            else {
                                                $proofImageSrc = base_url('uploads/' . $firstProofImage);
                                            }
                                        }
                                    } else {
                                        // Not JSON, check if it's a direct URL or filename
                                        if (strpos($proofImageData, 'http') === 0) {
                                            $proofImageSrc = $proofImageData;
                                        } else if (strpos($proofImageData, '/9j/') === 0 || strpos($proofImageData, 'iVBORw0KGgo') === 0) {
                                            $proofImageSrc = 'data:image/jpeg;base64,' . $proofImageData;
                                        } else {
                                            $proofImageSrc = base_url('uploads/' . $proofImageData);
                                        }
                                    }
                                }
                                ?>
                                <?php if (!empty($proofImageSrc)): ?>
                                    <div class="text-center">
                                        <img src="<?= $proofImageSrc ?>" 
                                             alt="Proof of Ownership" 
                                             class="img-fluid rounded shadow"
                                             style="max-height: 400px; cursor: pointer;"
                                             onclick="openImageModal(this.src)">
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted">
                                        <i class="bi bi-image-fill" style="font-size: 3rem;"></i>
                                        <p class="mt-2">No image available</p>
                                    </div>
                                <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Status and Resolution -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Claim Status Card -->
            <div class="glass-card sticky-status-card">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="bi bi-info-circle mr-3 text-blue-500"></i>Claim Status
                    </h2>
                    <p class="text-gray-600 mt-2">Current status and timeline</p>
                </div>
                
                <div class="space-y-6">
                    <!-- Status Badge -->
                    <div class="text-center">
                        <?php
                            $statusConfig = [
                                'approved' => ['bg' => 'bg-gradient-to-r from-green-500 to-emerald-600', 'text' => 'text-white', 'icon' => 'bi-check-circle-fill'],
                                'resolved' => ['bg' => 'bg-gradient-to-r from-green-500 to-emerald-600', 'text' => 'text-white', 'icon' => 'bi-check-circle-fill'],
                                'rejected' => ['bg' => 'bg-gradient-to-r from-red-500 to-rose-600', 'text' => 'text-white', 'icon' => 'bi-x-circle-fill'],
                                'pending' => ['bg' => 'bg-gradient-to-r from-amber-500 to-orange-600', 'text' => 'text-white', 'icon' => 'bi-clock-fill']
                            ];
                            $currentStatus = $claim['status'] ?? 'pending';
                            $config = $statusConfig[$currentStatus] ?? $statusConfig['pending'];
                        ?>
                        <div class="inline-flex items-center px-6 py-4 <?= $config['bg'] ?> <?= $config['text'] ?> rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-300">
                            <i class="bi <?= $config['icon'] ?> text-2xl mr-3"></i>
                            <div class="text-left">
                                <div class="text-sm font-medium opacity-90">Status</div>
                                <div class="text-xl font-bold"><?= ucfirst(esc($currentStatus)) ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Last Updated -->
                            <?php if (!empty($claim['lost_item_id'])): ?>
                                <a href="<?= site_url('items/view/' . $claim['lost_item_id']) ?>" class="action-button w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 block text-center rounded-xl text-white px-4 py-3 font-medium">
                                    <i class="bi bi-eye mr-2"></i>View Lost Item
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($claim['found_item_id'])): ?>
                                <a href="<?= site_url('items/view/' . $claim['found_item_id']) ?>" class="action-button w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 block text-center rounded-xl text-white px-4 py-3 font-medium">
                                    <i class="bi bi-eye mr-2"></i>View Found Item
                                </a>
                            <?php endif; ?>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Last Updated</label>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="bi bi-clock-history text-gray-500 mr-2"></i>
                                <span class="text-gray-900 font-medium"><?= date('F j, Y, g:i a', strtotime($claim['updated_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Quick Actions</label>
                        <div class="flex flex-col space-y-2">
                            <a href="<?= site_url('items/view/' . $claim['item_id']) ?>" 
                               class="action-button px-4 py-2 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center">
                                <i class="bi bi-box animated-icon mr-2"></i>View Item
                            </a>
                            <?php if (!empty($claim['match_id'])): ?>
                            <a href="<?= site_url('matches/view/' . $claim['match_id']) ?>" 
                               class="action-button px-4 py-2 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center">
                                <i class="bi bi-link-45deg animated-icon mr-2"></i>View Match
                            </a>
                            <?php endif; ?>
                            <a href="<?= site_url('claims/edit/' . $claim['id']) ?>" 
                               class="action-button px-4 py-2 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center">
                                <i class="bi bi-pencil-square animated-icon mr-2"></i>Edit
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Proof Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Proof Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>
<?= $this->endSection() ?>