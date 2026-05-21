<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Reply to Ticket #<?= $ticket['id'] ?>
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
    
    .ticket-card {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--cspc-primary);
    }
    
    .reply-form-card {
        background: rgba(248, 249, 250, 0.95);
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
    
    .ticket-user-info {
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
    
    .status-open {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-in-progress {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .status-resolved {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }
    
    .status-closed {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    .priority-low {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .priority-medium {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .priority-high {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .priority-urgent {
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
        color: #6366f1;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #4f46e5;
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
    
    .reply-page {
        background: transparent;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .reply-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .reply-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .card-header {
        background: #ffffff;
        color: #2c3e50;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .ticket-info {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .ticket-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }
    
    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .meta-item i {
        margin-right: 0.5rem;
        color: #667eea;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .status-open {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-in_progress {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-resolved {
        background: #d4edda;
        color: #155724;
    }
    
    .status-closed {
        background: #f8d7da;
        color: #721c24;
    }
    
    .priority-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        margin-left: 0.5rem;
    }
    
    .priority-low {
        background: #d4edda;
        color: #155724;
    }
    
    .priority-medium {
        background: #fff3cd;
        color: #856404;
    }
    
    .priority-high {
        background: #f8d7da;
        color: #721c24;
    }
    
    .priority-urgent {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .ticket-content {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .form-control[rows] {
        min-height: 150px;
        resize: vertical;
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    }
    
    .form-check {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .form-check-label {
        font-weight: 500;
        color: #2c3e50;
    }
    
    .help-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .page-title {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .page-title h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-title p {
        font-size: 1.1rem;
        opacity: 0.9;
        color: #64748b;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Modern Header Section -->
    <div class="glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="mb-3 mb-lg-0">
                        <h1 class="display-6 display-md-5 fw-bold text-dark mb-2">
                            <span class="text-gradient">Reply to Ticket</span>
                        </h1>
                        <p class="lead text-muted mb-3">Provide response and update ticket status</p>
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
                    <a href="<?= site_url('help-support/tickets/view/' . $ticket['id']) ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('help-support') ?>">Help & Support</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('help-support/tickets/view/' . $ticket['id']) ?>">View Ticket</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reply</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6">
            <!-- Original Ticket -->
            <div class="glass-card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-ticket-perforated text-primary me-2"></i>Ticket #<?= $ticket['id'] ?></h4>
                        <span class="status-badge status-<?= $ticket['status'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                        </span>
                    </div>
                    
                    <div class="ticket-user-info mb-4">
                        <div class="row g-4">
                            <div class="col-lg-6 col-md-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-person-circle text-primary"></i>
                                    <strong>From:</strong> 
                                    <span class="text-truncate"><?= esc($ticket['user_name']) ?></span>
                                </div>
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <i class="bi bi-envelope text-primary mt-1"></i>
                                    <div class="flex-grow-1">
                                        <strong>Email:</strong><br>
                                        <a href="mailto:<?= esc($ticket['user_email']) ?>" class="text-decoration-none text-break"><?= esc($ticket['user_email']) ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-calendar text-primary"></i>
                                    <strong>Date:</strong> 
                                    <span><?= date('M d, Y H:i A', strtotime($ticket['created_at'])) ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-tag text-primary"></i>
                                    <strong>Category:</strong> 
                                    <span class="badge bg-secondary ms-1"><?= ucwords(str_replace('_', ' ', esc($ticket['category']))) ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-flag text-primary"></i>
                                    <strong>Priority:</strong> 
                                    <span class="status-badge priority-<?= $ticket['priority'] ?> ms-1"><?= ucfirst($ticket['priority']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3"><?= esc($ticket['subject']) ?></h5>
                    </div>
                    
                    <div class="bg-light p-4 rounded-3 border-start border-4 border-primary mb-4">
                        <p class="mb-0"><?= nl2br(esc($ticket['description'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <!-- Reply Form -->
            <div class="glass-card">
                <div class="card-body p-4">
                    <h4 class="mb-4 fw-bold text-dark">
                        <i class="bi bi-reply text-primary me-2"></i> 
                        Write Reply
                    </h4>
                    
                    <form method="POST" action="<?= site_url('help-support/tickets/reply/' . $ticket['id']) ?>">
                        <?= csrf_field() ?>
                        
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="reply_email" name="reply_email" 
                                   value="<?= session()->get('email') ?>" readonly>
                            <label for="reply_email"><i class="bi bi-envelope me-1"></i>Reply From Email</label>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <textarea class="form-control" id="message" name="message" 
                                      placeholder="Write your reply here..." required 
                                      style="min-height: 200px;"><?= old('message') ?></textarea>
                            <label for="message"><i class="bi bi-chat-text me-1"></i>Reply Message *</label>
                            <?php if (isset($validation) && $validation->hasError('message')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('message') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold"><i class="bi bi-gear me-1"></i>Update Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Keep Current Status</option>
                                <option value="open" <?= old('status', $ticket['status']) == 'open' ? 'selected' : '' ?>>Open</option>
                                <option value="in_progress" <?= old('status', $ticket['status']) == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="resolved" <?= old('status', $ticket['status']) == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                <option value="closed" <?= old('status', $ticket['status']) == 'closed' ? 'selected' : '' ?>>Closed</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('status')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('status') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="is_internal" name="is_internal" value="1" <?= old('is_internal') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="is_internal">
                                <i class="bi bi-lock me-1"></i>
                                Internal Note Only
                            </label>
                            <div class="form-text">Check this if the reply is for internal use only and should not be sent to the customer.</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= site_url('help-support/tickets/view/' . $ticket['id']) ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> 
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            

        </div>
    </div>
<?= $this->endSection() ?>