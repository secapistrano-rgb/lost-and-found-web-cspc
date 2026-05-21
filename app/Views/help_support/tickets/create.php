<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .create-ticket-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .form-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
        border: none;
    }
    
    .card-header h5 {
        margin: 0;
        font-weight: 600;
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
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
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
    
    .help-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .page-title {
        color: white;
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
    }
    
    .tips-card {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .tip-section {
        margin-bottom: 1rem;
    }
    
    .tip-section h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .tip-section ul {
        margin: 0;
        padding-left: 1.2rem;
    }
    
    .tip-section li {
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="create-ticket-page">
    <div class="container">
        <!-- Page Title -->
        <div class="page-title">
            <h1><i class="bi bi-plus-circle me-3"></i>Create Support Ticket</h1>
            <p>Submit a new support request for assistance</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Create Ticket Form -->
                <div class="form-card">
                    <div class="card-header">
                        <h5><i class="bi bi-ticket-perforated me-2"></i>Ticket Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= site_url('help-support/tickets/store') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_name" class="form-label">
                                            <i class="bi bi-person me-1"></i>
                                            Your Name *
                                        </label>
                                        <input type="text" class="form-control" id="user_name" name="user_name" 
                                               value="<?= old('user_name') ?>" required>
                                        <div class="help-text">
                                            Enter your full name for identification.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_email" class="form-label">
                                            <i class="bi bi-envelope me-1"></i>
                                            Email Address *
                                        </label>
                                        <input type="email" class="form-control" id="user_email" name="user_email" 
                                               value="<?= old('user_email') ?>" required>
                                        <div class="help-text">
                                            We'll use this email to send you updates.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">
                                            <i class="bi bi-tag me-1"></i>
                                            Category *
                                        </label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Select a category</option>
                                            <option value="technical_issue" <?= old('category') === 'technical_issue' ? 'selected' : '' ?>>Technical Issue</option>
                                            <option value="account_problem" <?= old('category') === 'account_problem' ? 'selected' : '' ?>>Account Problem</option>
                                            <option value="matching_system" <?= old('category') === 'matching_system' ? 'selected' : '' ?>>Matching System</option>
                                            <option value="item_report_issue" <?= old('category') === 'item_report_issue' ? 'selected' : '' ?>>Item Report Issue</option>
                                            <option value="notification_problem" <?= old('category') === 'notification_problem' ? 'selected' : '' ?>>Notification Problem</option>
                                            <option value="other" <?= old('category') === 'other' ? 'selected' : '' ?>>Other</option>
                                        </select>
                                        <div class="help-text">
                                            Choose the category that best describes your issue.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="priority" class="form-label">
                                            <i class="bi bi-flag me-1"></i>
                                            Priority *
                                        </label>
                                        <select class="form-select" id="priority" name="priority" required>
                                            <option value="">Select priority level</option>
                                            <option value="low" <?= old('priority') === 'low' ? 'selected' : '' ?>>Low</option>
                                            <option value="medium" <?= old('priority') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                            <option value="high" <?= old('priority') === 'high' ? 'selected' : '' ?>>High</option>
                                            <option value="urgent" <?= old('priority') === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                                        </select>
                                        <div class="help-text">
                                            How urgent is this issue for you?
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">
                                    <i class="bi bi-card-heading me-1"></i>
                                    Subject *
                                </label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?= old('subject') ?>" placeholder="Brief description of your issue" required>
                                <div class="help-text">
                                    Provide a clear, concise subject line for your ticket.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    <i class="bi bi-chat-text me-1"></i>
                                    Description *
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="8" 
                                          placeholder="Please describe your issue in detail..." required><?= old('description') ?></textarea>
                                <div class="help-text">
                                    Provide as much detail as possible to help us understand and resolve your issue quickly.
                                </div>
                            </div>
                            
                            <!-- Attachment Field -->
                            <div class="mb-4">
                                <label for="attachment" class="form-label">
                                    <i class="bi bi-paperclip me-1"></i>
                                    Attachment (Optional)
                                </label>
                                <input type="file" class="form-control" id="attachment" name="attachment" 
                                       accept="image/*,.pdf,.doc,.docx,.txt">
                                <div class="help-text">
                                    You can attach screenshots, documents, or other files to help explain your issue (Max 10MB).
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?= site_url('help-support/tickets') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Back to Tickets
                                </a>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Create Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Tips Card -->
                <div class="tips-card">
                    <h5 class="mb-3"><i class="bi bi-lightbulb me-2"></i>Helpful Tips</h5>
                    
                    <div class="tip-section">
                        <h6><i class="bi bi-check-circle me-1"></i>Before Creating a Ticket:</h6>
                        <ul>
                            <li>Check our FAQ section for common solutions</li>
                            <li>Search existing tickets to avoid duplicates</li>
                            <li>Gather relevant information about your issue</li>
                        </ul>
                    </div>
                    
                    <div class="tip-section">
                        <h6><i class="bi bi-info-circle me-1"></i>Writing a Good Description:</h6>
                        <ul>
                            <li>Include steps to reproduce the issue</li>
                            <li>Mention any error messages you received</li>
                            <li>Describe what you expected to happen</li>
                            <li>Include screenshots if helpful</li>
                        </ul>
                    </div>
                    
                    <div class="tip-section">
                        <h6><i class="bi bi-clock me-1"></i>Response Times:</h6>
                        <ul>
                            <li><strong>Urgent:</strong> Within 2 hours</li>
                            <li><strong>High:</strong> Within 4 hours</li>
                            <li><strong>Medium:</strong> Within 24 hours</li>
                            <li><strong>Low:</strong> Within 48 hours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>