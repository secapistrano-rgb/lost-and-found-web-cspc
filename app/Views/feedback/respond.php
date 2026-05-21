<?php
// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Respond to Feedback - <?= esc($feedback['type']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Modern gradient background and glass card styling */
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
    
    .feedback-card {
        background: rgba(255, 255, 255, 0.95);
        /* backdrop-filter: blur(20px); */
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--cspc-primary);
    }
    
    .response-card {
        background: rgba(248, 249, 250, 0.95);
        /* backdrop-filter: blur(15px); */
        border: 1px solid rgba(222, 226, 230, 0.3);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }
    
    /* Text gradient styling */
    .text-gradient {
        background: linear-gradient(135deg, #000000 0%, #1e3a8a 50%, #581c87 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Rating stars styling */
    .rating-stars {
        color: #fbbf24;
        font-size: 1.2rem;
    }
    
    .feedback-user-info {
        background: rgba(233, 236, 239, 0.8);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }
    
    .form-floating textarea {
        min-height: 150px;
    }
    
    /* Modern form styling */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 1);
    }
    
    .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* Modern button styling */
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
        /* transform: translateY(-2px); */
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb, #1e40af);
    }
    
    .btn-outline-secondary {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid #6b7280;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        color: #6b7280;
    }
    
    .btn-outline-secondary:hover {
        /* transform: translateY(-2px); */
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        background: #6b7280;
        color: white;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .status-responded {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-resolved {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }
    
    /* Breadcrumb styling */
    .breadcrumb {
        background: rgba(248, 249, 250, 0.8);
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    
    .breadcrumb-item a {
        color: #111827; /* neutral dark */
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #1f2937; /* slightly darker */
    }
    
    /* Alert styling */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        backdrop-filter: blur(10px);
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        font-weight: 500;
    }
    
    .border-info .card-header {
        background: linear-gradient(135deg, #06b6d4, #0891b2) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Modern Header Section -->
     <div class="glass-card border-0 shadow-sm mb-4 rounded-2xl overflow-hidden">
         <div class="h-1 bg-blue-600"></div>
         <div class="card-body p-3 p-md-4 bg-white">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="mb-3 mb-lg-0">
                        <h1 class="display-6 display-md-5 fw-bold text-dark mb-2">Respond to Feedback</h1>
                        <p class="lead text-muted mb-3">Provide response and update feedback status</p>
                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 small text-muted">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                <span>Live Updates</span>
                            </div>
                            <div>Last Updated: <?= date('M d, Y \\a\\t g:i A') ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Back Button -->
                <div class="col-12 col-lg-4 text-lg-end">
                    <a href="<?= site_url('feedback/view/' . $feedback['id']) ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('feedback') ?>">Feedback Management</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('feedback/view/' . $feedback['id']) ?>">View Details</a></li>
            <li class="breadcrumb-item active" aria-current="page">Respond</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6">
            <!-- Original Feedback -->
            <div class="glass-card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-chat-square-text text-primary me-2"></i>Original Feedback</h4>
                        <span class="status-badge status-<?= $feedback['status'] ?>">
                            <?= ucfirst($feedback['status']) ?>
                        </span>
                    </div>
                    
                    <div class="feedback-user-info mb-4">
                        <div class="row g-4">
                            <div class="col-lg-6 col-md-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <?php if (!empty($feedback['user_photo_url'])): ?>
                                        <img src="<?= esc($feedback['user_photo_url']) ?>" 
                                             alt="<?= esc($feedback['user_name']) ?>" 
                                             class="rounded-circle" 
                                             style="width: 24px; height: 24px; object-fit: cover;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                        <i class="bi bi-person-circle text-primary" style="display: none;"></i>
                                    <?php else: ?>
                                        <i class="bi bi-person-circle text-primary"></i>
                                    <?php endif; ?>
                                    <strong>From:</strong> 
                                    <span class="text-truncate"><?= esc($feedback['user_name']) ?></span>
                                </div>
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <i class="bi bi-envelope text-primary mt-1"></i>
                                    <div class="flex-grow-1">
                                        <strong>Email:</strong><br>
                                        <a href="mailto:<?= esc($feedback['user_email']) ?>" class="text-decoration-none text-break"><?= esc($feedback['user_email']) ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-calendar text-primary"></i>
                                    <strong>Date:</strong> 
                                    <span><?= date('M d, Y g:i A', strtotime($feedback['created_at'])) ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-tag text-primary"></i>
                                    <strong>Type:</strong> 
                                    <span class="badge bg-secondary ms-1"><?= ucfirst(str_replace('_', ' ', $feedback['type'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3"><?= ucfirst(str_replace('_', ' ', $feedback['type'])) ?> Feedback</h5>
                        <?php if ($feedback['rating']): ?>
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-star text-warning"></i>
                                    <strong>Rating:</strong>
                                    <div class="rating-stars d-inline-block ms-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?= $i <= $feedback['rating'] ? '-fill' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-muted">(<?= $feedback['rating'] ?>/5)</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="bg-light p-4 rounded-3 border-start border-4 border-primary mb-4">
                        <p class="mb-0"><?= nl2br(esc($feedback['text'])) ?></p>
                    </div>
                    
                    <?php if (!empty($feedback['responses'])): ?>
                        <div class="mt-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-reply-all text-primary me-2"></i>Previous Responses:
                            </h6>
                            <?php foreach ($feedback['responses'] as $response): ?>
                                <div class="card border-info mb-3">
                                    <div class="card-header bg-info bg-opacity-10 border-info">
                                        <small class="text-muted">
                                            <i class="bi bi-person-badge me-1"></i>
                                            Response by <strong><?= esc($response['admin_name'] ?: $response['admin_email']) ?></strong> on 
                                            <strong><?= date('M d, Y g:i A', strtotime($response['created_at'])) ?></strong>
                                        </small>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0"><?= nl2br(esc($response['response_text'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <!-- Response Form -->
            <div class="glass-card">
                <div class="card-body p-4">
                    <h4 class="mb-4 fw-bold text-dark">
                        <i class="bi bi-reply text-primary me-2"></i> 
                        <?= !empty($feedback['responses']) ? 'Add New Response' : 'Write Response' ?>
                    </h4>
                    
                    <form method="POST" action="<?= site_url('feedback/update-response/' . $feedback['id']) ?>">
                        <?= csrf_field() ?>
                        
                        <div class="form-floating mb-4">
                            <textarea class="form-control" id="admin_response" name="admin_response" 
                                      placeholder="Write your response here..." required 
                                      style="min-height: 200px;"><?= old('admin_response') ?></textarea>
                            <label for="admin_response"><i class="bi bi-chat-text me-1"></i>Response Message *</label>
                            <?php if (isset($validation) && $validation->hasError('admin_response')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('admin_response') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold"><i class="bi bi-gear me-1"></i>Update Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="reviewed" <?= old('status', $feedback['status']) == 'reviewed' ? 'selected' : '' ?>>
                                    Reviewed (Awaiting user feedback)
                                </option>
                                <option value="addressed" <?= old('status', $feedback['status']) == 'addressed' ? 'selected' : '' ?>>
                                    Addressed (Issue closed)
                                </option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('status')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('status') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= site_url('feedback/view/' . $feedback['id']) ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> 
                                <?= !empty($feedback['responses']) ? 'Add Response' : 'Send Response' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Response Tips -->
            <div class="glass-card mt-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="bi bi-lightbulb text-info"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Response Tips</h6>
                    </div>
                    <ul class="mb-0 list-unstyled">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                            <span>Be professional and empathetic in your response</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                            <span>Address the specific concerns raised by the user</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                            <span>Provide clear next steps if applicable</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                            <span>Thank the user for their feedback</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                            <span>Set appropriate status based on resolution</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save draft functionality
    let autoSaveTimer;
    const responseTextarea = document.getElementById('admin_response');
    const feedbackId = '<?= isset($feedback['id']) ? esc($feedback['id'], 'js') : '' ?>';

    if (responseTextarea && feedbackId) {
        responseTextarea.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                try {
                    // Auto-save draft to localStorage
                    localStorage.setItem('feedback_response_draft_' + feedbackId, responseTextarea.value);
                } catch (e) {
                    console.warn('Could not save draft to localStorage:', e);
                }
            }, 2000);
        });
        
        // Load draft on page load
        try {
            const savedDraft = localStorage.getItem('feedback_response_draft_' + feedbackId);
            if (savedDraft && !responseTextarea.value) {
                responseTextarea.value = savedDraft;
            }
        } catch (e) {
            console.warn('Could not load draft from localStorage:', e);
        }
        
        // Clear draft on form submit
        const form = document.querySelector('form[action*="update-response"]');
        if (form) {
            form.addEventListener('submit', function() {
                try {
                    localStorage.removeItem('feedback_response_draft_' + feedbackId);
                } catch (e) {
                    console.warn('Could not clear draft from localStorage:', e);
                }
            });
        }
    }
});
</script>
<?= $this->endSection() ?>