<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Claim
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    
    .form-input {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .action-btn {
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        animation: buttonPulse 0.6s ease-in-out;
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    @keyframes buttonPulse {
        0%, 100% { transform: translateY(-2px) scale(1); }
        50% { transform: translateY(-2px) scale(1.05); }
    }
    
    @keyframes iconBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    
    .action-btn:hover svg {
        animation: iconBounce 0.6s ease-in-out;
    }
    
    .match-badge {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.9rem;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }
    
    .status-pending {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #8b4513;
    }
    
    .status-approved {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #2d5016;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
        color: #8b0000;
    }

.modern-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 2rem;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.card-header-modern {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
}

.item-comparison {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.item-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    border: 2px solid #f8f9fa;
    transition: all 0.3s ease;
}

.item-card.lost-item {
    border-left: 4px solid #dc3545;
}

.item-card.found-item {
    border-left: 4px solid #28a745;
}

.item-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.no-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-style: italic;
    margin-bottom: 1rem;
}

.item-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.item-category {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.category-lost {
    background-color: #f8d7da;
    color: #721c24;
}

.category-found {
    background-color: #d4edda;
    color: #155724;
}

.item-detail {
    margin-bottom: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.item-detail:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.detail-value {
    color: #6c757d;
}

.claimant-info {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #495057;
    flex: 0 0 30%;
}

.info-value {
    color: #6c757d;
    flex: 1;
    text-align: right;
}

.status-form {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    position: sticky;
    top: 2rem;
}

.status-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.status-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-cancel {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    text-decoration: none;
    display: inline-block;
    padding: 0.5rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.btn-cancel:hover {
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 3px 15px rgba(220, 53, 69, 0.2);
}

@media (max-width: 768px) {
    .item-comparison {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .claim-header {
        padding: 1rem 0;
    }
    
    .status-form {
        position: static;
        margin-top: 2rem;
    }
}
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <!-- Header Section -->
    <div class="max-w-6xl mx-auto mb-8">
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Edit Claim
                        </h1>
                        <p class="text-gray-600 mt-1">Review and update claim status</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="match-badge">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Match: <?= number_format($claim['score'] * 100, 0) ?>%
                    </div>
                    <a href="<?= site_url('claims') ?>" class="action-btn inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Claims
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->has('errors')) : ?>
        <div class="max-w-6xl mx-auto mb-6">
            <div class="glass-card rounded-xl p-4 border-l-4 border-red-500">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h5 class="font-semibold text-red-700">Validation Errors</h5>
                </div>
                <?php foreach (session('errors') as $error) : ?>
                    <p class="text-red-600 mb-1"><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
            <!-- Item Comparison Section -->
            <div class="glass-card rounded-2xl overflow-hidden shadow-2xl relative">
                <div class="absolute inset-x-0 top-0 h-1 bg-blue-600"></div>
                <!-- Section Header -->
                <div class="bg-white p-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white rounded-lg border border-gray-200">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Item Comparison</h2>
                            <p class="text-gray-500 mt-1">Lost vs Found Item Details</p>
                        </div>
                    </div>
                </div>

                <!-- Comparison Content -->
                <div class="p-8 bg-white/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Lost Item Card -->
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-100">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Lost Item</h3>
                            </div>
                            
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
                                <div class="mb-4">
                                    <img src="<?= $imageSrc ?>" alt="Lost: <?= esc($claim['lost_item_title']) ?>" class="w-full h-48 object-cover rounded-lg shadow-md">
                                </div>
                            <?php else: ?>
                                <div class="mb-4 w-full h-48 bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm">No image available</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Title:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['lost_item_title']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Category:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['lost_item_category']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-red-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Description:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['lost_item_description']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Location:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['lost_item_location']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Date Lost:</span>
                                        <p class="font-medium text-gray-800"><?= date('M d, Y', strtotime($claim['lost_item_date_lost_found'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Found Item Card -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-100">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Found Item</h3>
                            </div>
                            
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
                                <div class="mb-4">
                                    <img src="<?= $imageSrc ?>" alt="Found: <?= esc($claim['found_item_title']) ?>" class="w-full h-48 object-cover rounded-lg shadow-md">
                                </div>
                            <?php else: ?>
                                <div class="mb-4 w-full h-48 bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm">No image available</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Title:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['found_item_title']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Category:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['found_item_category']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Description:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['found_item_description']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Location:</span>
                                        <p class="font-medium text-gray-800"><?= esc($claim['found_item_location']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-white/60 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Date Found:</span>
                                        <p class="font-medium text-gray-800"><?= date('M d, Y', strtotime($claim['found_item_date_lost_found'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claimant Information Section -->
            <div class="glass-card rounded-2xl overflow-hidden shadow-2xl relative">
                <div class="absolute inset-x-0 top-0 h-1 bg-blue-600"></div>
                <!-- Section Header -->
                <div class="bg-white p-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white rounded-lg border border-gray-200">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Claimant Information</h2>
                            <p class="text-gray-500 mt-1">Contact details and claim information</p>
                        </div>
                    </div>
                </div>

                <!-- Claimant Content -->
                <div class="p-8 bg-white/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <div class="p-3 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 font-medium">Full Name</span>
                                <p class="text-sm font-semibold text-gray-800"><?= esc($claim['claimant_name'] ?? 'Not provided') ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 font-medium">Email Address</span>
                                <p class="text-sm font-semibold text-gray-800"><?= esc($claim['claimant_email'] ?? 'Not provided') ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 font-medium">Proof of Ownership</span>
                                <?php if (!empty($claim['proof_of_ownership'])): ?>
                                    <p class="text-sm text-gray-600"><?= esc($claim['proof_of_ownership']) ?></p>
                                <?php else: ?>
                                    <p class="text-lg font-semibold text-gray-500">Not provided</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <div class="p-3 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 font-medium">Submitted Date</span>
                                <p class="text-sm font-semibold text-gray-800"><?= date('F j, Y, g:i a', strtotime($claim['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Proof Image Section -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-200">
                        <div class="flex items-start">
                            <div class="p-3 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm text-gray-600 font-medium">Proof Image</span>
                                <?php if (!empty($claim['image'])): ?>
                                    <?php 
                                    $imageData = $claim['image'];
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
                                    <div class="mt-2">
                                        <img src="<?= $imageSrc ?>" alt="Proof Image" class="w-65 h-60 object-cover rounded-lg shadow-md">
                                        <p class="text-sm text-green-600 mt-1">✓ Image Uploaded</p>
                                    </div>
                                <?php else: ?>
                                    <p class="text-gray-500 mt-1">No proof image provided</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Update Form -->
            <div class="lg:col-span-1">
                <div class="glass-card rounded-2xl p-6 sticky top-8">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg text-white mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">Update Status</h4>
                    </div>
                
                    <!-- Current Status Display -->
                    <div class="mb-6 p-4 rounded-xl glass-card">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-700">Current Status:</span>
                            <span class="px-4 py-2 rounded-full text-sm font-medium <?php 
                                switch($claim['status']) {
                                    case 'pending': echo 'status-pending'; break;
                                    case 'approved': echo 'status-approved'; break;
                                    case 'rejected': echo 'status-rejected'; break;
                                    case 'resolved': echo 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white'; break;
                                    default: echo 'bg-gray-500 text-white';
                                }
                            ?>">
                                <?= ucfirst($claim['status']) ?>
                            </span>
                        </div>
                    </div>
                
                    <form action="<?= site_url('claims/update/' . $claim['id']) ?>" method="post" class="space-y-6">
                        <?= csrf_field() ?>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                New Status
                            </label>
                            <select id="status" name="status" class="form-input w-full px-4 py-3 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" <?= $claim['status'] == 'pending' ? 'selected' : '' ?>>
                                    ⏳ Pending Review
                                </option>
                                <option value="approved" <?= $claim['status'] == 'approved' ? 'selected' : '' ?>>
                                    ✅ Approved
                                </option>
                                <option value="rejected" <?= $claim['status'] == 'rejected' ? 'selected' : '' ?>>
                                    ❌ Denied
                                </option>
                                <option value="resolved" <?= $claim['status'] == 'resolved' ? 'selected' : '' ?>>
                                    ✨ Completed
                                </option>
                            </select>
                        </div>
                        
                        <button type="submit" class="action-btn w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Update Status
                        </button>
                    </form>
                
                    <!-- Status Guide -->
                    <div class="mt-6 p-4 rounded-xl glass-card border-l-4 border-blue-500">
                        <h6 class="flex items-center text-blue-700 font-semibold mb-3">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Guide
                        </h6>
                        <div class="text-sm text-gray-600 space-y-2">
                            <div><span class="font-medium">Pending Review:</span> Under admin review</div>
                            <div><span class="font-medium">Approved:</span> Claim verified, ready for pickup</div>
                            <div><span class="font-medium">Denied:</span> Claim not approved</div>
                            <div><span class="font-medium">Completed:</span> Item returned to owner</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
