<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
CSPC Lost & Found - Email Management
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Typography classes */
    .text-display {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 700;
        letter-spacing: -0.025em;
    }
    
    .text-subheading {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 500;
    }
    
    .text-caption {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 400;
    }
    
    .email-management-page {
        background-color: var(--cspc-light);
    }
    
    /* Glass card styling matching dashboard */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    /* Enhanced statistics cards matching dashboard style */
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.9));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card.card-total {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.9));
    }
    
    .stat-card.card-sent {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.8), rgba(56, 239, 125, 0.9));
    }
    
    .stat-card.card-failed {
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.8), rgba(238, 90, 36, 0.9));
    }
    
    .stat-card.card-pending {
        background: linear-gradient(135deg, rgba(255, 167, 38, 0.8), rgba(251, 140, 0, 0.9));
    }
    
    /* Add gradient top border like dashboard */
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }
    
    .stat-card.card-total::before {
        background: linear-gradient(90deg, #667eea, #764ba2);
    }
    
    .stat-card.card-sent::before {
        background: linear-gradient(90deg, #10b981, #38ef7d);
    }
    
    .stat-card.card-failed::before {
        background: linear-gradient(90deg, #ff6b6b, #ee5a24);
    }
    
    .stat-card.card-pending::before {
        background: linear-gradient(90deg, #ffa726, #fb8c00);
    }
    
    .stat-icon {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
    
    .stat-count {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-weight: 500;
    }
    
    /* Animation styles matching dashboard */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    

    
    /* Button styles matching dashboard */
    .btn-action {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    .btn-action:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Enhanced Header Section -->
    <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
            <div class="space-y-3">
                <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Email Management</h1>
                <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Monitor and manage email notifications across the system</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-caption text-xs">Live Updates</span>
                    </div>
                    <div class="text-caption text-xs">Last Updated: <?= date('M d, Y \a\t g:i A') ?></div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="lg:w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg id="searchIcon" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <div id="searchSpinner" class="h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin hidden"></div>
                    </div>
                    <input type="text" id="emailSearch" placeholder="Search emails, logs, or notifications..." class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center" role="alert">
            <i class="bi bi-check-circle-fill mr-2 text-green-600"></i>
            <span class="text-sm"><?= session()->getFlashdata('success') ?></span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                <i class="bi bi-x text-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill mr-2 text-red-600"></i>
            <span class="text-sm"><?= session()->getFlashdata('error') ?></span>
            <button type="button" class="ml-auto text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                <i class="bi bi-x text-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50/80 to-blue-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 mb-1">Total Emails</p>
                    <p class="text-2xl font-bold text-blue-900"><?= $stats['total'] ?></p>
                    <p class="text-xs text-blue-600">All notifications</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-envelope text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-50/80 to-green-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 0.5s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 via-green-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700 mb-1">Sent Successfully</p>
                    <p class="text-2xl font-bold text-green-900"><?= $stats['sent'] ?></p>
                    <p class="text-xs text-green-600">Delivered emails</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-50/80 to-red-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-400 via-red-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-700 mb-1">Failed</p>
                    <p class="text-2xl font-bold text-red-900"><?= $stats['failed'] ?></p>
                    <p class="text-xs text-red-600">Delivery errors</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-50/80 to-orange-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1.5s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 via-orange-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-700 mb-1">Pending Send</p>
                    <p class="text-2xl font-bold text-orange-900"><?= $stats['pending'] ?></p>
                    <p class="text-xs text-orange-600">In queue</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-clock text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    
    <div class="w-full">
        <!-- Main Content -->
        <div class="w-full">
            <!-- Enhanced Recent Emails -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-xl border border-white/30 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400"></div>
                <div class="relative z-10">
                    <div class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 px-6 py-4 border-b border-gray-100/50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl p-2 mr-3">
                                    <i class="bi bi-clock-history text-blue-600 text-lg"></i>
                                </div>
                                Recent Email Activity
                            </h6>
                            <div class="flex rounded-xl border border-white/30 overflow-hidden backdrop-blur-sm bg-white/50">
                                <button type="button" class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-sm" data-filter="all">All</button>
                                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white/70 transition-all duration-200" data-filter="sent">Sent</button>
                                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white/70 transition-all duration-200" data-filter="failed">Failed</button>
                                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white/70 transition-all duration-200" data-filter="pending">Pending Send</button>
                            </div>
                        </div>
                    </div>
        <div class="overflow-hidden">
            <?php if (empty($recent_emails)): ?>
                <div class="text-center py-12">
                    <i class="bi bi-inbox text-6xl text-gray-400 mb-4"></i>
                    <h5 class="text-lg font-medium text-gray-900 mb-2">No emails sent yet</h5>
                    <p class="text-gray-600 mb-6">Start by composing your first email</p>
                    <a href="<?= site_url('emails/compose') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i> Compose Email
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full" id="recentEmailsTable">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50/80 to-blue-50/50 backdrop-blur-sm">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Recipient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Subject</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Sent By</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200/50">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-gray-100/50">
                            <?php foreach ($recent_emails as $email): ?>
                                <tr data-status="<?= $email['status'] ?>" class="hover:bg-white/70 transition-all duration-200 backdrop-blur-sm">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="bi bi-person text-gray-500 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?= esc($email['recipient_name'] ?: $email['recipient_email']) ?></div>
                                                <div class="text-sm text-gray-500"><?= esc($email['recipient_email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 truncate max-w-xs" title="<?= esc($email['subject']) ?>">
                                            <?= esc($email['subject']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $statusConfig = [
                                            'sent' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'check-circle-fill'],
                                            'failed' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'x-circle-fill'],
                                            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock-fill']
                                        ];
                                        $config = $statusConfig[$email['status']] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'question-circle'];
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $config['class'] ?>">
                                            <i class="bi bi-<?= $config['icon'] ?> mr-1"></i>
                                            <?= ucfirst($email['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500"><?= esc($email['sent_by']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($email['created_at'])) ?></div>
                                        <div class="text-sm text-gray-500"><?= date('g:i A', strtotime($email['created_at'])) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="viewEmailDetails(<?= $email['id'] ?>)" title="View Details">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-md hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="deleteEmail(<?= $email['id'] ?>)" title="Delete Email">
                                                <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            <?php if ($email['status'] === 'failed'): ?>
                                                <button type="button" class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-medium rounded-md hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-sm hover:shadow-lg transform hover:scale-110 hover:-translate-y-1 group" onclick="resendEmail(<?= $email['id'] ?>)" title="Resend">
                                                    <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                         </tbody>
                     </table>
                 </div>
                 
                 <!-- Pagination -->
                 <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                 <div class="bg-white px-6 py-3 border-t border-gray-200/50">
                     <div class="flex items-center justify-between">
                         <div class="text-sm text-gray-700">
                             Showing <?= $pager->getFirstItem() ?> to <?= $pager->getLastItem() ?> of <?= $pager->getTotal() ?> results
                         </div>
                         <div class="pagination-wrapper">
                              <?= $pager->links('emails', 'default_full') ?>
                          </div>
                     </div>
                 </div>
                 <?php endif; ?>
                 
                 <div class="bg-gray-50/80 backdrop-blur-sm px-6 py-3 border-t border-gray-200/50 text-center">
                     <a href="<?= site_url('emails/logs') ?>" class="btn-action">
                         <i class="bi bi-list-ul"></i> View All Email Logs
                     </a>
                 </div>
             <?php endif; ?>
                 </div>
                </div>
             </div>
        </div>
    </div>
</div>

<style>
/* Typography classes */
.text-display {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 700;
    letter-spacing: -0.025em;
    line-height: 1.2;
}

.text-subheading {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 500;
    line-height: 1.5;
}

.text-caption {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 400;
    letter-spacing: 0.025em;
}

.text-body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 400;
    line-height: 1.6;
}

/* Glass morphism effects */
.glass {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.glass-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 16px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    transition: all 0.3s ease;
    position: relative;
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: inherit;
    pointer-events: none;
    z-index: 1;
}

.glass-card > * {
    position: relative;
    z-index: 2;
}

.glass-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.45);
}

.glass-button {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

/* Animation classes */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes glow {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.3); }
    50% { box-shadow: 0 0 30px rgba(255, 255, 255, 0.5); }
}

@keyframes shimmer {
    0% { background-position: -200px 0; }
    100% { background-position: calc(200px + 100%) 0; }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-glow {
    animation: glow 3s ease-in-out infinite;
}

.animate-shimmer {
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    background-size: 200px 100%;
    animation: shimmer 2s infinite;
}

/* Custom styles for enhanced interactions */
.filter-btn-active {
    background-color: #2563eb !important;
    color: white !important;
}

.filter-btn-inactive {
    color: #374151;
}

.filter-btn-inactive:hover {
    background-color: #f9fafb;
}

/* Smooth transitions for interactive elements */
.transition-all {
    transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
// Filter functionality for recent emails
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const tableRows = document.querySelectorAll('#recentEmailsTable tbody tr');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('text-gray-700', 'hover:bg-gray-50');
            });
            this.classList.remove('text-gray-700', 'hover:bg-gray-50');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Filter rows
            tableRows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (filter === 'all' || status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});

// Email details modal function
function viewEmailDetails(id) {
    // Redirect to email details page
    window.location.href = `<?= site_url('emails/view') ?>/${id}`;
}

// Resend email function
function resendEmail(id) {
    if (confirm('Are you sure you want to resend this email?')) {
        // Implementation for resending email
        console.log('Resend email for ID:', id);
        // You can implement the resend functionality here
    }
}

// Delete email function
function deleteEmail(id) {
    if (confirm('Are you sure you want to delete this email? This action cannot be undone.')) {
        // Show loading state
        const deleteButton = document.querySelector(`button[onclick="deleteEmail(${id})"]`);
        const originalContent = deleteButton.innerHTML;
        deleteButton.innerHTML = '<svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
        deleteButton.disabled = true;
        
        // Make AJAX request
        fetch(`<?= site_url('emails/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from table
                const row = deleteButton.closest('tr');
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    row.remove();
                    showNotification('Email deleted successfully', 'success');
                    
                    // Update statistics if needed
                    location.reload();
                }, 300);
            } else {
                showNotification(data.message || 'Failed to delete email', 'error');
                deleteButton.innerHTML = originalContent;
                deleteButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the email', 'error');
            deleteButton.innerHTML = originalContent;
            deleteButton.disabled = false;
        });
    }
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    // Set colors based on type
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        notification.className += ' bg-red-500 text-white';
    } else {
        notification.className += ' bg-blue-500 text-white';
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Show/hide search loading indicator
function showSearchLoading() {
    const searchIcon = document.getElementById('searchIcon');
    const searchSpinner = document.getElementById('searchSpinner');
    
    if (searchIcon && searchSpinner) {
        searchIcon.classList.add('hidden');
        searchSpinner.classList.remove('hidden');
    }
}

function hideSearchLoading() {
    const searchIcon = document.getElementById('searchIcon');
    const searchSpinner = document.getElementById('searchSpinner');
    
    if (searchIcon && searchSpinner) {
        searchIcon.classList.remove('hidden');
        searchSpinner.classList.add('hidden');
    }
}

// Email search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('emailSearch');
    const searchButton = document.getElementById('searchButton');
    const emailTable = document.getElementById('recentEmailsTable');
    
    function performSearch() {
        showSearchLoading();
        
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // Simulate loading delay for better UX
        setTimeout(() => {
            if (emailTable) {
                const rows = emailTable.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    let found = false;
                    
                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                    });
                    
                    if (found || searchTerm === '') {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            hideSearchLoading();
        }, 300);
    }
    
    // Search on button click
    if (searchButton) {
        searchButton.addEventListener('click', performSearch);
    }
    
    // Search on Enter key press
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Real-time search as user types
        searchInput.addEventListener('input', performSearch);
    }
});
</script>

<?= $this->endSection() ?>