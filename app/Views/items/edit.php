<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Item
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
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <!-- Header Section -->
    <div class="max-w-6xl mx-auto mb-8">
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Edit Item
                        </h1>
                        <p class="text-gray-600 mt-1">Update item information and details</p>
                    </div>
                </div>
                <a href="<?= site_url('items') ?>" class="action-btn inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->has('errors')) : ?>
        <div class="max-w-6xl mx-auto mb-6">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-red-800 font-medium">Please fix the following errors:</h3>
                </div>
                <?php foreach (session('errors') as $error) : ?>
                    <p class="text-red-700 ml-7"><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Form Container -->
    <div class="max-w-6xl mx-auto">
        <div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
            <!-- Form Header -->
            <div class="bg-white p-6 border-t-4 border-blue-500">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-50 rounded-lg border border-blue-200">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Edit Item Details</h2>
                        <p class="text-gray-600 mt-1">Update and modify item information</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8 bg-white/50">
            <form action="<?= site_url('items/update/' . $item['id']) ?>" method="post" enctype="multipart/form-data">
                 <?= csrf_field() ?>

                 <!-- Basic Information Section -->
                 <div class="mb-8">
                     <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                         <div class="flex items-center mb-4">
                             <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg mr-3">
                                 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>
                             </div>
                             <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                         </div>
                         
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                 <label for="title" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                     </svg>
                                     Item Name
                                 </label>
                                 <input type="text" id="title" name="title" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['title'] ?? '') ?>" required>
                             </div>
                             <div>
                                 <label for="category" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                     </svg>
                                     Category
                                 </label>
                                 <input type="text" id="category" name="category" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['category'] ?? '') ?>" placeholder="Enter category..." required>
                             </div>
                         </div>
                         
                         <div class="mt-6">
                             <label for="description" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                 <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                 </svg>
                                 Description
                             </label>
                             <textarea id="description" name="description" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" rows="4" required><?= esc($item['description'] ?? '') ?></textarea>
                         </div>
                     </div>
                 </div>

                <!-- Contact Information Section -->
                 <div class="mb-8">
                     <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-100">
                         <div class="flex items-center mb-4">
                             <div class="p-2 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg mr-3">
                                 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                 </svg>
                             </div>
                             <h3 class="text-lg font-semibold text-gray-800">Contact Information</h3>
                         </div>
                         
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                 <label for="reporter_email" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                     </svg>
                                     Contact Email
                                 </label>
                                 <input type="email" id="reporter_email" name="reporter_email" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['reporter_email'] ?? '') ?>" required>
                             </div>
                             <div>
                                 <label for="reporter_name" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                     </svg>
                                     Reporter Name
                                 </label>
                                 <input type="text" id="reporter_name" name="reporter_name" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['reporter_name'] ?? '') ?>" placeholder="Enter reporter name...">
                             </div>
                         </div>
                         
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                             <div>
                                 <label for="reporter_department" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                     </svg>
                                     Department
                                 </label>
                                 <input type="text" id="reporter_department" name="reporter_department" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['reporter_department'] ?? '') ?>" placeholder="Enter department...">
                             </div>
                             <div>
                                 <label for="location" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                     </svg>
                                     Location
                                 </label>
                                 <input type="text" id="location" name="location" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['location'] ?? '') ?>" placeholder="Enter location where item was found/lost...">
                             </div>
                         </div>
                     </div>
                 </div>

                <!-- Item Details Section -->
                 <div class="mb-8">
                     <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                         <div class="flex items-center mb-4">
                             <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg mr-3">
                                 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                 </svg>
                             </div>
                             <h3 class="text-lg font-semibold text-gray-800">Item Details</h3>
                         </div>
                         
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                             <div>
                                 <label for="date_lost_found" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                     </svg>
                                     Date Found/Lost
                                 </label>
                                 <input type="date" id="date_lost_found" name="date_lost_found" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" value="<?= esc($item['date_lost_found'] ? date('Y-m-d', strtotime($item['date_lost_found'])) : '') ?>" required>
                             </div>
                             <div>
                                 <label for="type" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                     </svg>
                                     Type
                                 </label>
                                 <select id="type" name="type" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" required>
                                     <option value="">Select Type...</option>
                                     <option value="lost" <?= ($item['type'] ?? '') == 'lost' ? 'selected' : '' ?>>Lost</option>
                                     <option value="found" <?= ($item['type'] ?? '') == 'found' ? 'selected' : '' ?>>Found</option>
                                 </select>
                             </div>
                             <div>
                                 <label for="status" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                     </svg>
                                     Status
                                 </label>
                                 <select id="status" name="status" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" required>
                                     <option value="active" <?= ($item['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                     <option value="inactive" <?= ($item['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                     <option value="claimed" <?= ($item['status'] ?? '') == 'claimed' ? 'selected' : '' ?>>Awaiting Pickup</option>
                                     <option value="returned" <?= ($item['status'] ?? '') == 'returned' ? 'selected' : '' ?>>Successfully Returned</option>
                                     <option value="Pending Turning-in" <?= ($item['status'] ?? '') == 'Pending Turning-in' ? 'selected' : '' ?>>Pending Turning-in</option>
                                     <option value="Fake Report" <?= ($item['status'] ?? '') == 'Fake Report' ? 'selected' : '' ?>>Fake Report</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                 </div>

                <!-- Image Upload Section -->
                 <div class="mb-8">
                     <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-100">
                         <div class="flex items-center mb-4">
                             <div class="p-2 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg mr-3">
                                 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                 </svg>
                             </div>
                             <h3 class="text-lg font-semibold text-gray-800">Item Image</h3>
                         </div>
                         
                         <div class="space-y-4">
                             <div>
                                 <label for="images" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                     <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                     </svg>
                                     Upload New Image
                                 </label>
                                 <input type="file" id="images" name="images" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none" onchange="previewImage()" accept="image/*">
                             </div>
                             
                             <!-- Current Image Display -->
                             <?php 
                             $imageFile = '';
                             $imageUrl = '';
                             if (!empty($item['images'])) {
                                 // Enhanced image URL handling logic
                                if (strpos($item['images'], 'data:image') === 0) {
                                    // Base64 encoded image with data URI prefix
                                    $imageUrl = $item['images'];
                                } else {
                                    // Try to decode as JSON first
                                    $images = json_decode($item['images'], true);
                                    if (is_array($images) && !empty($images)) {
                                        $firstImage = $images[0];
                                        
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
                             
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                 <?php if ($imageUrl): ?>
                                     <div>
                                         <p class="text-sm font-medium text-gray-700 mb-2">Current Image:</p>
                                         <div class="relative bg-white rounded-xl p-4 border-2 border-dashed border-amber-200">
                                             <img src="<?= $imageUrl ?>" alt="Current Image" class="w-full max-h-48 object-contain rounded-lg">
                                         </div>
                                     </div>
                                 <?php endif; ?>
                                 
                                 <div id="preview-container" style="display: none;">
                                     <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                                     <div class="relative bg-white rounded-xl p-4 border-2 border-dashed border-amber-200">
                                         <img id="image-preview" src="#" alt="Image Preview" class="w-full max-h-48 object-contain rounded-lg"/>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Item
                    </button>
                    <a href="<?= base_url('items') ?>" class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:from-gray-600 hover:to-gray-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function previewImage() {
        const fileInput = document.getElementById('images');
        const preview = document.getElementById('image-preview');
        
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>