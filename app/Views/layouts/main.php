<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <style>
        :root {
            --cspc-primary: #003366;
            --cspc-secondary: #004d99;
            --cspc-accent: #0066cc;
            --cspc-dark: #002040;
            --cspc-light: #f0f2f5;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cspc-light);
            color: var(--cspc-dark);
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(rgba(0, 32, 64, 0.85), rgba(0, 32, 64, 0.85)), url('<?= base_url('images/school-background.jpg') ?>');
            background-size: cover;
            background-position: center;
            padding: 1.5rem;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header img {
            width: 80px; 
            height: 80px; 
            border-radius: 50%;
        }
        
        .sidebar-header h3 {
            color: #fff;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav a {
            color: #fff;
            text-decoration: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .sidebar-nav a.active {
            background: var(--cspc-accent);
            color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .sidebar-nav i {
            margin-right: 0.75rem;
            width: 20px;
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
            background: var(--cspc-light);
        }

        .stat-card {
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            color: var(--cspc-dark);
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('images/logo.jpg') ?>" alt="CSPC Logo" class="mb-2" style="width: 80px; height: 80px; border-radius: 50%;">
            <h3>Lost & Found Admin</h3>
        </div>
        <nav class="sidebar-nav">
            <li>
                <a href="<?= site_url('dashboard') ?>" class="<?= isActive('dashboard') ?>">
                    <i class="bi bi-grid"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?= site_url('items') ?>" class="<?= isActive('items') ?>">
                    <i class="bi bi-box-seam"></i>
                    Item Management
                </a>
            </li>
            <li>
                <a href="<?= site_url('claims') ?>" class="<?= isActive('claims') ?>">
                    <i class="bi bi-person-check"></i>
                    Claim Management
                </a>
            </li>
            <li>
                <a href="<?= site_url('users') ?>" class="<?= isActive('users') ?>">
                    <i class="bi bi-people"></i>
                    User Management
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin_role') ?>" class="<?= isActive('admin_role') ?>">
                    <i class="bi bi-person-badge"></i>
                    Role Management
                </a>
            </li>
        </nav>
    </div>

    <div class="main-content">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
