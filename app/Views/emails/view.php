<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Email Details
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
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-sent {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-failed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #374151;
        min-width: 150px;
    }
    
    .info-value {
        color: #6b7280;
        text-align: right;
        flex: 1;
    }
    
    .email-content {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
        white-space: pre-wrap;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
    }
    
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
    }
    
    .btn-action:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
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
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
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
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        color: white;
        text-decoration: none;
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
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Email Details</h1>
                    <p class="text-muted mb-0">View detailed information about this email</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('emails') ?>" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Emails
                    </a>
                    <?php if ($email['status'] === 'failed'): ?>
                        <button onclick="resendEmail(<?= $email['id'] ?>)" class="btn-action">
                            <i class="fas fa-redo"></i>
                            Resend Email
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Email Information -->
        <div class="col-lg-8">
            <div class="glass-card mb-4">
                <h4 class="mb-4">Email Information</h4>
                
                <div class="info-item">
                    <span class="info-label">Recipient Email:</span>
                    <span class="info-value"><?= esc($email['recipient_email']) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Recipient Name:</span>
                    <span class="info-value"><?= esc($email['recipient_name'] ?: 'N/A') ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Subject:</span>
                    <span class="info-value"><?= esc($email['subject']) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Sent By:</span>
                    <span class="info-value"><?= esc($email['sent_by_name']) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Created At:</span>
                    <span class="info-value"><?= date('M j, Y g:i A', strtotime($email['created_at'])) ?></span>
                </div>
                
                <?php if ($email['sent_at']): ?>
                <div class="info-item">
                    <span class="info-label">Sent At:</span>
                    <span class="info-value"><?= date('M j, Y g:i A', strtotime($email['sent_at'])) ?></span>
                </div>
                <?php endif; ?>
                
                <div class="info-item">
                    <span class="info-label">Attempts:</span>
                    <span class="info-value"><?= esc($email['attempts'] ?? 1) ?></span>
                </div>
            </div>
            
            <!-- Email Content -->
            <div class="glass-card">
                <h4 class="mb-3">Email Content</h4>
                <div class="email-content"><?= nl2br(esc($email['body'])) ?></div>
            </div>
        </div>
        
        <!-- Status and Actions -->
        <div class="col-lg-4">
            <div class="glass-card mb-4 sticky-status-card">
                <h4 class="mb-4">Status</h4>
                
                <div class="text-center mb-4">
                    <span class="status-badge status-<?= $email['status'] ?>">
                        <?= ucfirst($email['status']) ?>
                    </span>
                </div>
                
                <?php if ($email['error_message']): ?>
                <div class="alert alert-danger">
                    <h6>Error Message:</h6>
                    <p class="mb-0"><?= esc($email['error_message']) ?></p>
                </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2">
                    <?php if ($email['status'] === 'failed'): ?>
                        <button onclick="resendEmail(<?= $email['id'] ?>)" class="btn-action w-100">
                            <i class="fas fa-redo"></i>
                            Resend Email
                        </button>
                    <?php endif; ?>
                    
                    <!-- Delete functionality removed - Email system is now view-only -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resendEmail(id) {
    if (confirm('Are you sure you want to resend this email?')) {
        fetch(`<?= site_url('emails/resend') ?>/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Email resent successfully!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Failed to resend email', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while resending the email', 'error');
        });
    }
}

// Delete email function removed - Email system is now view-only

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<?= $this->endSection() ?>