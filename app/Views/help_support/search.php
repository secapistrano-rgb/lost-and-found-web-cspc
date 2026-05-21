<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .search-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .search-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        margin-bottom: 2rem;
    }
    
    .search-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
    }
    
    .search-form {
        padding: 2rem;
    }
    
    .search-input {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .result-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .result-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .result-header {
        padding: 1.5rem 1.5rem 0.5rem;
    }
    
    .result-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-decoration: none;
    }
    
    .result-title:hover {
        color: #667eea;
    }
    
    .result-content {
        padding: 0 1.5rem 1.5rem;
        color: #6c757d;
        line-height: 1.6;
    }
    
    .result-meta {
        padding: 0 1.5rem 1.5rem;
        border-top: 1px solid #f8f9fa;
        margin-top: 1rem;
        padding-top: 1rem;
    }
    
    .type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .type-faq {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .type-announcement {
        background: #fff3e0;
        color: #f57c00;
    }
    
    .type-ticket {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    .category-badge {
        background: #f8f9fa;
        color: #495057;
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        margin-left: 0.5rem;
    }
    
    .no-results {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .no-results i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }
    
    .search-stats {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="search-page">
    <div class="container">
        <!-- Search Form -->
        <div class="card search-card">
            <div class="search-header">
                <h2 class="mb-0">
                    <i class="bi bi-search me-2"></i>
                    Search Help & Support
                </h2>
            </div>
            <div class="search-form">
                <form method="GET" action="<?= site_url('help-support/search') ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="q" class="form-label">Search Query</label>
                            <input type="text" class="form-control search-input" id="q" name="q" 
                                   value="<?= esc($query ?? '') ?>" placeholder="Enter your search terms..." required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Content Type</label>
                            <select class="form-select search-input" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="faq" <?= ($type ?? '') === 'faq' ? 'selected' : '' ?>>FAQs</option>
                                <option value="announcement" <?= ($type ?? '') === 'announcement' ? 'selected' : '' ?>>Announcements</option>
                                <option value="ticket" <?= ($type ?? '') === 'ticket' ? 'selected' : '' ?>>Tickets</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-search w-100">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($query) && $query): ?>
            <!-- Search Results -->
            <div class="search-stats">
                <i class="bi bi-info-circle me-2"></i>
                Found <?= count($results) ?> result(s) for "<strong><?= esc($query) ?></strong>"
                <?php if ($type): ?>
                    in <strong><?= ucfirst($type) ?></strong>
                <?php endif; ?>
            </div>

            <?php if (!empty($results)): ?>
                <?php foreach ($results as $result): ?>
                    <div class="card result-card">
                        <div class="result-header">
                            <h5 class="result-title">
                                <?= esc($result['title']) ?>
                            </h5>
                        </div>
                        <div class="result-content">
                            <?= character_limiter(strip_tags($result['content']), 200) ?>
                        </div>
                        <div class="result-meta">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="type-badge type-<?= $result['type'] ?>">
                                        <?= ucfirst($result['type']) ?>
                                    </span>
                                    <?php if (!empty($result['category'])): ?>
                                        <span class="category-badge">
                                            <?= esc($result['category']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    <?= date('M j, Y', strtotime($result['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card result-card">
                    <div class="no-results">
                        <i class="bi bi-search"></i>
                        <h4>No Results Found</h4>
                        <p>We couldn't find any content matching your search criteria.</p>
                        <p class="mb-0">Try using different keywords or check the spelling.</p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="<?= site_url('help-support') ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Help & Support
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>