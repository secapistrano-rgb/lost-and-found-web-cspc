<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Match Details - #<?= $match['id'] ?? 'Unknown' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Enhanced Glass card styling */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 2rem;
    }
    
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }
    
    .sticky-status-card {
        position: sticky;
        top: 2rem;
        z-index: 10;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }
    
    /* Enhanced scrollbar styling */
    .sticky-status-card::-webkit-scrollbar {
        width: 6px;
    }
    
    .sticky-status-card::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
    
    .sticky-status-card::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 3px;
    }
    
    .sticky-status-card::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
    }
    
    /* Enhanced match score styling */
    .match-score {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .match-score::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #f5576c);
        z-index: -1;
        animation: rotate 3s linear infinite;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .score-high {
        background: linear-gradient(135deg, #10b981, #059669, #34d399);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    }
    
    .score-medium {
        background: linear-gradient(135deg, #f59e0b, #d97706, #fbbf24);
        box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
    }
    
    .score-low {
        background: linear-gradient(135deg, #ef4444, #dc2626, #f87171);
        box-shadow: 0 15px 35px rgba(239, 68, 68, 0.4);
    }
    
    /* Enhanced timeline styling */
    .status-timeline {
        position: relative;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(148, 163, 184, 0.1);
    }
    
    .status-timeline::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 60px;
        bottom: 20px;
        width: 3px;
        background: linear-gradient(to bottom, #3b82f6, #8b5cf6, transparent);
        border-radius: 2px;
    }
    
    /* Enhanced table styling */
    .comparison-table {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .comparison-table thead th {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.875rem;
    }
    
    .comparison-table tbody tr:nth-child(even) {
        background: rgba(248, 250, 252, 0.5);
    }
    
    .comparison-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.05);
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    /* Enhanced image container */
    .image-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .image-container:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .image-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .image-container:hover::after {
        opacity: 1;
    }
    
    /* Enhanced status cards */
    .status-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Enhanced buttons */
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
    /* Header accent line */
    .header-accent {
        border-top: 4px solid #3b82f6;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="glass-card header-accent mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">
                    Match Details
                </h1>
                <p class="text-gray-600">Review and manage match information</p>
            </div>
            <div class="flex gap-3">
                <a href="<?= site_url('matches') ?>" 
                   class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-300 shadow-sm hover:shadow-md">
                    <i class="bi bi-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Match and Item Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Match Comparison Table -->
            <div class="glass-card">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Item Comparison</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full comparison-table">
                            <thead>
                                <tr>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Aspect</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Lost Item</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Found Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <!-- Images Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Images</td>
                                     <td class="py-5 px-6">
                                         <div class="aspect-video w-36 bg-gray-100 image-container">
                                             <?php if (!empty($match['lost_item_images'] ?? '')): ?>
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
                                                 
                                                 if (!empty($imageSources)): 
                                                 ?>
                                                     <img src="<?= $imageSources[0] ?>" alt="Lost Item" class="w-full h-full object-cover">
                                                 <?php else: ?>
                                                     <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                         <i class="bi bi-image text-2xl"></i>
                                                     </div>
                                                 <?php endif; ?>
                                             <?php else: ?>
                                                 <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                     <i class="bi bi-image text-2xl"></i>
                                                 </div>
                                             <?php endif; ?>
                                         </div>
                                     </td>
                                     <td class="py-5 px-6">
                                         <div class="aspect-video w-36 bg-gray-100 image-container">
                                             <?php if (!empty($match['found_item_images'] ?? '')): ?>
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
                                                 
                                                 if (!empty($imageSources)): 
                                                 ?>
                                                     <img src="<?= $imageSources[0] ?>" alt="Found Item" class="w-full h-full object-cover">
                                                 <?php else: ?>
                                                     <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                         <i class="bi bi-image text-2xl"></i>
                                                     </div>
                                                 <?php endif; ?>
                                             <?php else: ?>
                                                 <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                     <i class="bi bi-image text-2xl"></i>
                                                 </div>
                                             <?php endif; ?>
                                         </div>
                                     </td>
                                 </tr>
                                 
                                 <!-- Title & Category Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Title</td>
                                     <td class="py-5 px-6">
                                         <a href="<?= site_url('items/view/' . $match['lost_item_id']) ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                             <?= esc($match['lost_item_title']) ?>
                                         </a>
                                     </td>
                                     <td class="py-5 px-6">
                                         <a href="<?= site_url('items/view/' . $match['found_item_id']) ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                             <?= esc($match['found_item_title']) ?>
                                         </a>
                                     </td>
                                 </tr>
                                 
                                 <!-- Title & Category Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Title & Category</td>
                                     <td class="py-5 px-6">
                                         <div class="space-y-2">
                                             <p class="font-semibold text-gray-900 text-lg"><?= esc($match['lost_item_title'] ?? 'No title provided') ?></p>
                                             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"><?= esc($match['lost_item_category'] ?? 'Not specified') ?></span>
                                         </div>
                                     </td>
                                     <td class="py-5 px-6">
                                         <div class="space-y-2">
                                             <p class="font-semibold text-gray-900 text-lg"><?= esc($match['found_item_title'] ?? 'No title provided') ?></p>
                                             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"><?= esc($match['found_item_category'] ?? 'Not specified') ?></span>
                                         </div>
                                     </td>
                                 </tr>
                                 
                                 <!-- Description Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Description</td>
                                     <td class="py-5 px-6">
                                         <p class="text-gray-700 leading-relaxed"><?= esc($match['lost_item_description'] ?? 'No description provided') ?></p>
                                     </td>
                                     <td class="py-5 px-6">
                                         <p class="text-gray-700 leading-relaxed"><?= esc($match['found_item_description'] ?? 'No description provided') ?></p>
                                     </td>
                                 </tr>
                                 
                                 <!-- Location Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Location</td>
                                     <td class="py-5 px-6">
                                         <div class="flex items-center space-x-2">
                                             <i class="bi bi-geo-alt text-red-500"></i>
                                             <p class="text-gray-700 font-medium"><?= esc($match['lost_item_location'] ?? 'Not specified') ?></p>
                                         </div>
                                     </td>
                                     <td class="py-5 px-6">
                                         <div class="flex items-center space-x-2">
                                             <i class="bi bi-geo-alt text-green-500"></i>
                                             <p class="text-gray-700 font-medium"><?= esc($match['found_item_location'] ?? 'Not specified') ?></p>
                                         </div>
                                     </td>
                                 </tr>
                                 
                                 <!-- Date Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Date</td>
                                     <td class="py-5 px-6">
                                         <div class="flex items-center space-x-2">
                                             <i class="bi bi-calendar text-blue-500"></i>
                                             <p class="text-gray-700 font-medium"><?= date('M d, Y', strtotime($match['lost_item_date_lost_found'] ?? 'now')) ?></p>
                                         </div>
                                     </td>
                                     <td class="py-5 px-6">
                                         <div class="flex items-center space-x-2">
                                             <i class="bi bi-calendar text-green-500"></i>
                                             <p class="text-gray-700 font-medium"><?= date('M d, Y', strtotime($match['found_item_date_lost_found'] ?? 'now')) ?></p>
                                         </div>
                                     </td>
                                 </tr>
                                 
                                 <!-- Reporter Row -->
                                 <tr>
                                     <td class="py-5 px-6 font-semibold text-gray-800">Reporter</td>
                                     <td class="py-5 px-6">
                                         <div class="space-y-2">
                                             <div class="flex items-center space-x-2">
                                                 <i class="bi bi-person text-blue-500"></i>
                                                 <p class="text-gray-900 font-medium"><?= esc($match['lost_item_reporter_name'] ?? 'Not provided') ?></p>
                                             </div>
                                             <div class="flex items-center space-x-2">
                                                 <i class="bi bi-envelope text-gray-400"></i>
                                                 <p class="text-gray-600"><?= esc($match['lost_item_reporter_email'] ?? 'Not provided') ?></p>
                                             </div>
                                         </div>
                                     </td>
                                     <td class="py-5 px-6">
                                         <div class="space-y-2">
                                             <div class="flex items-center space-x-2">
                                                 <i class="bi bi-person text-green-500"></i>
                                                 <p class="text-gray-900 font-medium"><?= esc($match['found_item_reporter_name'] ?? 'Not provided') ?></p>
                                             </div>
                                             <div class="flex items-center space-x-2">
                                                 <i class="bi bi-envelope text-gray-400"></i>
                                                 <p class="text-gray-600"><?= esc($match['found_item_reporter_email'] ?? 'Not provided') ?></p>
                                             </div>
                                         </div>
                                     </td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
             
             <!-- Additional Match Information -->
             <div class="glass-card">
                 <div class="p-6">
                     <h2 class="text-xl font-semibold text-gray-800 mb-6">Additional Match Information</h2>
                     
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div class="status-card">
                             <div class="flex items-center justify-between mb-3">
                                 <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Match ID</span>
                                 <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                     <i class="bi bi-hash text-white text-lg"></i>
                                 </div>
                             </div>
                             <p class="text-sm font-bold text-gray-900 font-mono">#<?= esc($match['id'] ?? 'Unknown') ?></p>
                         </div>
                         
                         <div class="status-card">
                             <div class="flex items-center justify-between mb-3">
                                 <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Match Status</span>
                                 <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                                     <i class="bi bi-flag text-white text-lg"></i>
                                 </div>
                             </div>
                             <?php
                             $status = $match['status'] ?? 'pending';
                             $statusColors = [
                                 'pending' => 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg shadow-yellow-500/25',
                                 'accepted' => 'bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-lg shadow-green-500/25',
                                 'rejected' => 'bg-gradient-to-r from-red-400 to-rose-500 text-white shadow-lg shadow-red-500/25',
                                 'resolved' => 'bg-gradient-to-r from-blue-400 to-indigo-500 text-white shadow-lg shadow-blue-500/25'
                             ];
                             $statusIcons = [
                                 'pending' => 'bi-clock',
                                 'accepted' => 'bi-check-circle',
                                 'rejected' => 'bi-x-circle',
                                 'resolved' => 'bi-check2-all'
                             ];
                             ?>
                             <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold <?= $statusColors[$status] ?? $statusColors['pending'] ?>">
                                 <i class="bi <?= $statusIcons[$status] ?? $statusIcons['pending'] ?> mr-2"></i>
                                 <?= ucfirst($status) ?>
                             </span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         
         <!-- Right Column - Status and Actions -->
         <div class="lg:col-span-1">
             <div class="glass-card sticky-status-card">
                 <div class="p-6">
                     <h2 class="text-xl font-semibold text-gray-800 mb-6">Match Status</h2>
                     
                     <div class="space-y-6">
                         <!-- Confidence Score -->
                         <div class="text-center">
                             <div class="match-score <?= (($match['score'] ?? 0) * 100) >= 80 ? 'score-high' : ((($match['score'] ?? 0) * 100) >= 60 ? 'score-medium' : 'score-low') ?>">
                                <?= number_format(($match['score'] ?? 0) * 100, 1) ?>%
                             </div>
                             <p class="text-sm text-gray-600 mt-2">Match Confidence</p>
                         </div>
                         
                         <!-- Status Timeline -->
                         <div class="status-timeline">
                             <h3 class="font-semibold text-gray-800 mb-4">Timeline</h3>
                             
                             <div class="space-y-6">
                                 <div class="timeline-item flex items-start space-x-4">
                                     <div class="timeline-icon flex-shrink-0 w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                                         <i class="bi bi-exclamation-triangle text-white text-sm"></i>
                                     </div>
                                     <div class="timeline-content flex-1">
                                         <h4 class="timeline-title text-sm font-semibold text-gray-900 mb-1">Lost Item Reported</h4>
                                         <p class="timeline-date text-xs text-gray-500"><?= date('M d, Y g:i A', strtotime($match['lost_item_date_lost_found'] ?? 'now')) ?></p>
                                     </div>
                                 </div>
                                 
                                 <div class="timeline-item flex items-start space-x-4">
                                     <div class="timeline-icon flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                         <i class="bi bi-check-circle text-white text-sm"></i>
                                     </div>
                                     <div class="timeline-content flex-1">
                                         <h4 class="timeline-title text-sm font-semibold text-gray-900 mb-1">Found Item Reported</h4>
                                         <p class="timeline-date text-xs text-gray-500"><?= date('M d, Y g:i A', strtotime($match['found_item_date_lost_found'] ?? 'now')) ?></p>
                                     </div>
                                 </div>
                                 
                                 <div class="timeline-item flex items-start space-x-4">
                                     <div class="timeline-icon flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                         <i class="bi bi-link text-white text-sm"></i>
                                     </div>
                                     <div class="timeline-content flex-1">
                                         <h4 class="timeline-title text-sm font-semibold text-gray-900 mb-1">Match Created</h4>
                                         <p class="timeline-date text-xs text-gray-500"><?= date('M d, Y g:i A', strtotime($match['created_at'] ?? 'now')) ?></p>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         
                         <!-- Actions -->
                         <div class="space-y-3">
                             <h3 class="font-semibold text-gray-800">Quick Actions</h3>
                             
                             <div class="grid grid-cols-1 gap-2">
                                 <a href="<?= site_url('matches/edit/' . ($match['id'] ?? '')) ?>" class="action-button w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 block text-center">
                                     <i class="bi bi-pencil-square mr-2"></i>Edit Match Status
                                 </a>
                                 
                                 <div class="space-y-2">
                                     <a href="<?= site_url('items/view/' . ($match['lost_item_id'] ?? '')) ?>" class="action-button w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 block text-center">
                                         <i class="bi bi-eye mr-2"></i>View Lost Item
                                     </a>
                                     <a href="<?= site_url('items/view/' . ($match['found_item_id'] ?? '')) ?>" class="action-button w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 block text-center">
                                         <i class="bi bi-eye mr-2"></i>View Found Item
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

<?= $this->endSection() ?>
