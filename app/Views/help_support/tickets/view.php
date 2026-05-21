<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
.ticket-view-container {
    background: #f8fafc;
    min-height: 100vh;
    padding: 2rem 0;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.ticket-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    color: #2d3748;
    border-radius: 20px 20px 0 0;
    padding: 2rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.priority-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.conversation-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.conversation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 500;
    margin-right: 1rem;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.8);
}

.admin-avatar {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.user-avatar-default {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 3px solid rgba(139, 92, 246, 0.2);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.profile-image {
        border: 3px solid rgba(139, 92, 246, 0.2);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    .user-info-card {
        background: rgba(248, 249, 250, 0.8);
        border: 1px solid rgba(222, 226, 230, 0.3);
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .user-info-card:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

.reply-content {
    background: rgba(248, 250, 252, 0.5);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
    border-left: 3px solid #e2e8f0;
    border: 1px solid rgba(226, 232, 240, 0.3);
}

.sidebar-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.sidebar-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: #3b82f6;
    border: 1px solid #3b82f6;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background: #f8fafc;
    border: 1px solidrgb(255, 255, 255);
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    color: #64748b;
}

.btn-secondary:hover {
    background:rgb(171, 179, 188);
    border-color:rgb(133, 148, 167);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(148, 163, 184, 0.2);
}

/* Sidebar Cards */
.sidebar-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.sidebar-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: rgba(102, 126, 234, 0.3);
}

.sidebar-card .border-bottom {
    border-color: rgba(0, 0, 0, 0.1) !important;
}

.sidebar-card h5 {
    color: #2d3748;
}

.sidebar-card .bg-light {
    background: rgba(248, 250, 252, 0.8) !important;
    border: 1px solid rgba(226, 232, 240, 0.5);
    transition: all 0.3s ease;
}

.sidebar-card .bg-light:hover {
    background: rgba(237, 242, 247, 0.9) !important;
    border-color: rgba(102, 126, 234, 0.2);
}

.sidebar-card a {
    color: #667eea;
    transition: color 0.3s ease;
}

.sidebar-card a:hover {
    color: #764ba2;
}

/* Ticket info stats alignment */
.ticket-info-stat { display: flex; flex-direction: column; align-items: center; justify-content: center; }
.ticket-info-icon { width: 50px; height: 50px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
.ticket-id { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace; font-weight: 700; font-size: 0.95rem; line-height: 1.2; word-break: break-all; text-align: center; }

/* Modern Gradient Button Styles */
.btn-glass {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: #374151;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.btn-glass:hover {
    background: rgba(255, 255, 255, 0.35);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    color: #1f2937;
}

.btn-glass:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-glass:hover:before {
    left: 100%;
}

/* Enhanced Status Badges with Gradients */
.status-badge.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.status-badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.status-badge.bg-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.status-badge.bg-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
}

.priority-badge.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.priority-badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.priority-badge.bg-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

/* Enhanced Glass Card Hover Effects */
.glass-card {
    position: relative;
    overflow: hidden;
}

.glass-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
    z-index: 1;
}

.glass-card:hover:before {
    left: 100%;
}

.glass-card > * {
    position: relative;
    z-index: 2;
}

/* Enhanced Form Controls */
.form-control {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
    transform: translateY(-1px);
}
.ticket-info-divider { width: 1px; background: rgba(0,0,0,0.1); height: 100%; min-height: 72px; }

/* Quick Actions */
.quick-actions .btn { display: block; width: 100%; padding: 0.75rem 1rem; border-radius: 10px; font-weight: 600; }
.quick-actions .btn + .btn { margin-top: 0.75rem; }
.quick-actions .btn-respond { background: #2563eb; border: 1px solid #2563eb; color: #fff; }
.quick-actions .btn-respond:hover { background: #1d4ed8; border-color: #1d4ed8; }
.quick-actions .btn-delete { background: #ef4444; border: 1px solid #ef4444; color: #fff; }
.quick-actions .btn-delete:hover { background: #dc2626; border-color: #dc2626; }
.quick-actions .btn-back { background: #ffffff; border: 1px solid #e5e7eb; color: #374151; }
.quick-actions .btn-back:hover { background: #f9fafb; border-color: #d1d5db; }

/* Unified action button styling (consistent across views) */
.action-button { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 10px 16px; border-radius: 12px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%); box-shadow: 0 10px 20px rgba(79, 70, 229, 0.25); border: none; position: relative; overflow: hidden; transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease; text-decoration: none; }
.action-button::after { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent); transition: left 0.6s ease; }
.action-button:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 14px 30px rgba(79, 70, 229, 0.35); filter: brightness(1.08); }
.action-button:hover::after { left: 100%; }
.action-button .animated-icon { transition: transform 0.25s ease; will-change: transform; }
.action-button:hover .animated-icon { transform: translateX(2px); }
</style>

<div class="ticket-view-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="text-white mb-2">Support Ticket</h2>
                        <p class="text-white-50 mb-0">Manage and respond to customer inquiries</p>
                    </div>
                    <div>
                        <a href="<?= site_url('help-support/tickets') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Ticket Details -->
                <div class="glass-card">
                    <div class="ticket-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="mb-3 fw-bold"><?= esc($ticket['subject']) ?></h3>
                                <div class="d-flex gap-2 mb-2">
                                    <span class="status-badge bg-light text-dark"><?= ucfirst($ticket['category']) ?></span>
                                    <?php
                                    $priorityColors = [
                                        'high' => 'danger',
                                        'medium' => 'warning',
                                        'low' => 'success'
                                    ];
                                    $priorityColor = $priorityColors[$ticket['priority']] ?? 'secondary';
                                    ?>
                                    <span class="priority-badge bg-<?= $priorityColor ?> text-white"><?= ucfirst($ticket['priority']) ?> Priority</span>
                                    <?php
                                    $statusColors = [
                                        'open' => 'warning',
                                        'in_progress' => 'info',
                                        'resolved' => 'success',
                                        'closed' => 'secondary'
                                    ];
                                    $statusColor = $statusColors[$ticket['status']] ?? 'secondary';
                                    ?>
                                    <span class="status-badge bg-<?= $statusColor ?> text-white"><?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?></span>
                                </div>
                            </div>
                            <?php if ($ticket['status'] !== 'closed'): ?>
                                <div class="flex flex-wrap gap-2">
                                    <?php if ($ticket['status'] === 'open'): ?>
                                        <button type="button" 
                                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg" 
                                                onclick="updateStatus('in_progress')">
                                            <i class="fas fa-play me-2"></i>Start Progress
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($ticket['status'] === 'in_progress'): ?>
                                        <button type="button" 
                                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg" 
                                                onclick="updateStatus('resolved')">
                                            <i class="fas fa-check me-2"></i>Mark Resolved
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($ticket['status'] === 'resolved'): ?>
                                        <button type="button" 
                                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg" 
                                                onclick="updateStatus('closed')">
                                            <i class="fas fa-times me-2"></i>Close Ticket
                                        </button>
                                    <?php endif; ?>
                                    
                                    <a href="#replyForm" 
                                       class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                        <i class="fas fa-reply me-2"></i>Reply
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="d-flex align-items-start">
                            <div class="user-avatar user-avatar-default me-3">
                                <?= strtoupper(substr($ticket['user_name'], 0, 1)) ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?= esc($ticket['user_name']) ?></h6>
                                        <small class="text-muted"><?= esc($ticket['user_email']) ?></small>
                                    </div>
                                    <small class="text-muted"><?= date('M j, Y g:i A', strtotime($ticket['created_at'])) ?></small>
                                </div>
                                <div class="reply-content">
                                    <p class="mb-0 lh-lg"><?= nl2br(esc($ticket['description'])) ?></p>
                                    
                                    <!-- Attachment Display -->
                                    <?php if (!empty($ticket['attachment_url']) && !empty($ticket['attachment_filename'])): ?>
                                        <?php 
                                        // Check if attachment URL is a valid web-accessible path
                                        $attachmentUrl = $ticket['attachment_url'];
                                        $isExternalUrl = (strpos($attachmentUrl, 'http://') === 0 || strpos($attachmentUrl, 'https://') === 0);
                                        $isLocalPath = (strpos($attachmentUrl, 'uploads/') === 0 || strpos($attachmentUrl, '/uploads/') === 0 || strpos($attachmentUrl, 'writable/') === 0);
                                        $isMobilePath = (strpos($attachmentUrl, 'file:/') === 0);
                                        
                                        // Determine final URL
                                        if ($isExternalUrl) {
                                            $finalUrl = $attachmentUrl; // Use as-is for external URLs
                                        } elseif ($isLocalPath) {
                                            $finalUrl = base_url($attachmentUrl); // Use base_url for local paths
                                        } else {
                                            $finalUrl = null; // Not accessible
                                        }
                                        ?>
                                        <div class="mt-3 p-3 bg-light rounded border">
                                            <h6 class="mb-2 text-muted"><i class="fas fa-paperclip me-2"></i>Attachment:</h6>
                                            <?php if ($finalUrl): ?>
                                                <a href="<?= esc($finalUrl) ?>" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-2"></i><?= esc($ticket['attachment_filename']) ?>
                                                </a>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="fas fa-file me-2"></i>
                                                    <span><?= esc($ticket['attachment_filename']) ?></span>
                                                    <small class="ms-2 text-warning">(File not accessible from web)</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Replies -->
                <?php if (!empty($replies)): ?>
                    <div class="glass-card mt-4">
                        <div class="p-4 border-bottom">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-comments me-2 text-primary"></i>
                                Conversation (<?= count($replies) ?> replies)
                            </h5>
                        </div>
                        <div class="p-4">
                            <?php foreach ($replies as $reply): ?>
                                <div class="conversation-card p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="user-avatar <?= ($reply['responder_type'] === 'super_admin') ? 'admin-avatar' : 'user-avatar-default' ?> me-3">
                                            <?= strtoupper(substr($reply['responder_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1 fw-bold">
                                                        <?= esc($reply['responder_name']) ?>
                                                        <?php if ($reply['responder_type'] === 'super_admin'): ?>
                                                            <span class="badge bg-success ms-2"><i class="fas fa-shield-alt me-1"></i>Support Team</span>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <small class="text-muted"><?= esc($reply['responder_email']) ?></small>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i><?= date('M j, Y g:i A', strtotime($reply['created_at'])) ?></small>
                                            </div>
                                            <div class="reply-content">
                                                <p class="mb-0 lh-lg"><?= nl2br(esc($reply['message'])) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                </div>
                
                <div class="col-lg-4">
                <!-- Ticket Details Card -->
                <div class="glass-card mb-4">
                    <div class="p-4 border-bottom bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="fas fa-ticket-alt text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold text-gray-800">Ticket Details</h5>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-heading text-primary me-2"></i>
                                <label class="form-label fw-bold mb-0 text-sm">Subject:</label>
                            </div>
                            <p class="mb-0 ms-4 text-gray-800"><?= esc($ticket['subject']) ?></p>
                        </div>
                        
                        <div class="mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                <label class="form-label fw-bold mb-0 text-sm">Created:</label>
                            </div>
                            <p class="mb-0 ms-4"><?= date('M j, Y g:i A', strtotime($ticket['created_at'])) ?></p>
                        </div>
                        
                        <?php if ($ticket['updated_at'] !== $ticket['created_at']): ?>
                            <div class="mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <label class="form-label fw-bold mb-0 text-sm">Last Updated:</label>
                                </div>
                                <p class="mb-0 ms-4"><?= date('M j, Y g:i A', strtotime($ticket['updated_at'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-comments text-info me-2"></i>
                                <label class="form-label fw-bold mb-0 text-sm">Replies:</label>
                            </div>
                            <p class="mb-0 ms-4"><?= count($replies) ?> response(s)</p>
                        </div>
                        
                        <?php if (!empty($ticket['attachment_url']) && !empty($ticket['attachment_filename'])): ?>
                            <?php 
                            // Check if attachment URL is a valid web-accessible path
                            $attachmentUrl = $ticket['attachment_url'];
                            $isExternalUrl = (strpos($attachmentUrl, 'http://') === 0 || strpos($attachmentUrl, 'https://') === 0);
                            $isLocalPath = (strpos($attachmentUrl, 'uploads/') === 0 || strpos($attachmentUrl, '/uploads/') === 0 || strpos($attachmentUrl, 'writable/') === 0);
                            $isMobilePath = (strpos($attachmentUrl, 'file:/') === 0);
                            
                            // Determine final URL
                            if ($isExternalUrl) {
                                $finalUrl = $attachmentUrl; // Use as-is for external URLs
                            } elseif ($isLocalPath) {
                                $finalUrl = base_url($attachmentUrl); // Use base_url for local paths
                            } else {
                                $finalUrl = null; // Not accessible
                            }
                            
                            $isAccessible = ($finalUrl !== null);
                            ?>
                            <div class="mb-0 p-3 <?= $isAccessible ? 'bg-blue-50 border-blue-200' : 'bg-gray-100 border-gray-300' ?> rounded-3 border">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-paperclip <?= $isAccessible ? 'text-blue-600' : 'text-gray-500' ?> me-2"></i>
                                    <label class="form-label fw-bold mb-0 text-sm <?= $isAccessible ? 'text-blue-700' : 'text-gray-600' ?>">Attachment:</label>
                                </div>
                                <?php if ($isAccessible): ?>
                                    <a href="<?= esc($finalUrl) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary w-100 mt-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-download me-2"></i>
                                        <span class="text-truncate"><?= esc($ticket['attachment_filename']) ?></span>
                                    </a>
                                <?php else: ?>
                                    <div class="mt-2 p-2 bg-white rounded text-center">
                                        <div class="d-flex align-items-center justify-content-center text-muted small">
                                            <i class="fas fa-file me-2"></i>
                                            <span class="text-truncate"><?= esc($ticket['attachment_filename']) ?></span>
                                        </div>
                                        <small class="text-warning d-block mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            File uploaded from mobile app - not accessible from web
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card mt-4">
                    <div class="p-4 border-bottom">
                        <div class="flex items-center space-x-2">
                            <!-- <div class="p-2 bg-yellow-500 rounded-lg">
                                <i class="fas fa-bolt text-white"></i>
                            </div> -->
                            <h5 class="mb-0 fw-bold text-gray-800">Quick Actions</h5>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <a href="<?= site_url('help-support/tickets/reply/' . $ticket['id']) ?>" 
                               class="action-button d-inline-flex align-items-center justify-content-center w-100">
                                <i class="fas fa-reply me-2 animated-icon"></i>
                                Respond
                            </a>
                            
                            <button type="button" 
                                    class="action-button d-inline-flex align-items-center justify-content-center w-100" 
                                    onclick="deleteTicket('<?= esc($ticket['id']) ?>')">
                                <i class="fas fa-trash me-2 animated-icon"></i>
                                Delete
                            </button>
                            
                            <a href="<?= site_url('help-support/tickets') ?>" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 btn-glass text-gray-700 font-medium rounded-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
// Update ticket status
function updateStatus(newStatus) {
    if (confirm(`Are you sure you want to update this ticket status to "${newStatus.replace('_', ' ')}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('help-support/tickets/update-status/' . $ticket['id']) ?>';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Delete ticket
function deleteTicket(ticketId) {
    if (confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('help-support/tickets/delete/') ?>' + ticketId;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

// Clear reply form
function clearForm() {
    document.getElementById('content').value = '';
    document.getElementById('status').selectedIndex = 0;
    document.getElementById('notify_user').checked = true;
}

// Auto-scroll to reply form if hash is present
if (window.location.hash === '#replyForm') {
    document.getElementById('replyForm').scrollIntoView({ behavior: 'smooth' });
}
</script>
<?= $this->endSection() ?>