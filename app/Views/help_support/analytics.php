<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .analytics-page {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
        min-height: 100vh;
        padding: 3rem 0;
        position: relative;
        overflow-x: hidden;
    }
    
    .analytics-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        pointer-events: none;
    }
    
    .analytics-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
        color: white;
        border-radius: 1.5rem 1.5rem 0 0;
        padding: 2rem;
        border: none;
        position: relative;
        overflow: hidden;
    }
    

    
    .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 1.5rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 2.5rem 2rem;
        text-align: center;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .stat-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.2rem;
        color: white;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .stat-icon.tickets {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
    }
    
    .stat-icon.faqs {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 50%, #ff9ff3 100%);
    }
    
    .stat-icon.announcements {
        background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 50%, #00d2ff 100%);
    }
    
    .stat-icon.resolved {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 50%, #11998e 100%);
    }
    
    .stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e3c72, #667eea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    
    .filter-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 1.5rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 2.5rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1e3c72, #2a5298, #667eea);
    }
    
    .form-control, .form-select {
        border-radius: 1rem;
        border: 2px solid rgba(230, 230, 230, 0.8);
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        font-weight: 500;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), 0 8px 25px rgba(102, 126, 234, 0.1);
        background: rgba(255, 255, 255, 1);
    }
    
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
        border: none;
        border-radius: 1rem;
        padding: 1rem 2.5rem;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.8);
        color: white;
        border-radius: 1rem;
        padding: 1rem 2.5rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    
    .list-group-item {
        border: none;
        border-radius: 1rem;
        margin-bottom: 0.75rem;
        background: rgba(248, 249, 250, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1.25rem;
    }
    
    .badge {
        padding: 0.6rem 1.2rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .bg-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #667eea 100%) !important;
    }
    
    .bg-warning {
        background: linear-gradient(135deg, #ff9a56 0%, #ffad56 100%) !important;
    }
    
    .chart-container {
        position: relative;
        height: 350px;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        backdrop-filter: blur(10px);
    }
    
    .trend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        background: rgba(248, 249, 250, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .trend-date {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    
    .trend-count {
        background: linear-gradient(135deg, #1e3c72 0%, #667eea 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 4rem;
        position: relative;
        z-index: 1;
    }
    
    .page-title h1 {
        font-size: 4rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        letter-spacing: -1px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .page-title p {
        font-size: 1.3rem;
        opacity: 0.95;
        font-weight: 400;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .page-title i {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="analytics-page">
    <div class="container">
        <!-- Page Title -->
        <div class="page-title">
            <h1><i class="bi bi-graph-up me-3"></i>Analytics Dashboard</h1>
            <p>Comprehensive insights into your help & support system</p>
        </div>

        <!-- Date Filter -->
        <div class="filter-card">
            <form method="GET" action="<?= site_url('help_support/analytics') ?>">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="<?= $start_date ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="<?= $end_date ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-2"></i>Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon tickets">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <div class="stat-number"><?= $ticket_stats['total'] ?? 0 ?></div>
                    <div class="stat-label">Total Tickets</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon resolved">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-number"><?= $ticket_stats['resolved'] ?? 0 ?></div>
                    <div class="stat-label">Resolved Tickets</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon faqs">
                        <i class="bi bi-question-circle"></i>
                    </div>
                    <div class="stat-number"><?= count($faq_stats ?? []) ?></div>
                    <div class="stat-label">Active FAQs</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon announcements">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div class="stat-number"><?= $ticket_stats['avg_resolution_time'] ?? 0 ?></div>
                    <div class="stat-label">Avg Resolution (hrs)</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Most Viewed FAQs -->
            <div class="col-md-6 mb-4">
                <div class="analytics-card">
                    <div class="card-header">
                        <h5><i class="bi bi-eye me-2"></i>Most Viewed FAQs</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($faq_stats)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($faq_stats, 0, 10) as $faq): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= character_limiter($faq['title'], 40) ?></h6>
                                            <small class="text-muted"><?= $faq['category'] ?></small>
                                        </div>
                                        <span class="badge bg-primary"><?= $faq['view_count'] ?> views</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No FAQ data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Common Problem Categories -->
            <div class="col-md-6 mb-4">
                <div class="analytics-card">
                    <div class="card-header">
                        <h5><i class="bi bi-tags me-2"></i>Common Problem Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($common_problems)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($common_problems as $problem): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0"><?= ucfirst($problem['category']) ?></h6>
                                        </div>
                                        <span class="badge bg-warning"><?= $problem['count'] ?> tickets</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No category data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Trends -->
        <div class="analytics-card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up me-2"></i>Daily Ticket Trends</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($daily_trends)): ?>
                    <div class="row">
                        <?php foreach ($daily_trends as $trend): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="trend-item">
                                    <div class="trend-date">
                                        <?= date('M j', strtotime($trend['date'])) ?>
                                    </div>
                                    <div class="trend-count">
                                        <?= $trend['tickets_created'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-graph-up text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No trend data available for the selected period</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?= site_url('help-support') ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>