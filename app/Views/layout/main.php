<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= $this->renderSection('title') ?> - CSPC Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 1rem 0;
            margin-bottom: 1rem;
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
            margin: 0.5rem 0 0;
            font-size: 1.2rem;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar-nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            background: rgba(255,255,255,0.05);
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: var(--cspc-accent);
            color: #fff;
        }
        .sidebar-nav i {
            margin-right: 0.75rem;
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .user-box {
            background: rgba(0,0,0,0.2);
            border-radius: 0.75rem;
            padding: 0.75rem;
        }
        .user-profile-link {
            text-decoration: none;
            display: block;
            border-radius: 0.5rem;
        }
        .user-profile-link:hover .user-profile {
            background-color: rgba(255,255,255,0.08);
        }
        .user-profile {
            display: flex;
            align-items: center;
            color: #fff;
            margin-bottom: 0.75rem;
        }
        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .user-info {
            flex: 1;
            min-width: 0; /* Prevents flex item from overflowing its container */
        }
        .user-info p,
        .user-info small {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-info p {
            margin: 0;
            font-size: 0.9rem;
            font-weight: bold;
            line-height: 1.2;
        }
        .user-info small {
            font-size: 0.75rem;
            opacity: 0.8;
            display: block;
        }
        .logout-button {
            display: block;
            width: 100%;
            padding: 0.5rem;
            text-align: center;
            background-color: rgba(255, 0, 0, 0.2);
            color: #ffcdd2;
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .logout-button:hover {
            background-color: rgba(255, 0, 0, 0.4);
            color: #fff;
        }
        .logout-button i {
            margin-right: 0.5rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        /* Stat Cards */
        .stat-card {
            padding: 1.5rem;
            border-radius: 1rem;
            color: var(--cspc-dark);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .stat-card.card-total {
            background-color: #a9b7ff;
        }
        .stat-card.card-claimed {
            background-color: #a8e6cf;
        }
        .stat-card.card-pending {
            background-color: #d7b9ff;
        }
        .stat-icon {
            font-size: 1.2rem;
            background: rgba(0,0,0,0.08);
            border-radius: 0.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .stat-count {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }
        .stat-label {
            margin: 0.25rem 0 0;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('images/logo.jpg') ?>" alt="CSPC Logo">
            <h3>Lost & Found</h3>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= site_url('dashboard') ?>" class="<?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="<?= site_url('items') ?>" class="<?= (strpos(uri_string(), 'items') === 0) ? 'active' : '' ?>">
                <i class="bi bi-box-seam-fill"></i> Item Management
            </a>
            <a href="<?= site_url('claims') ?>" class="<?= (strpos(uri_string(), 'claims') === 0) ? 'active' : '' ?>">
                <i class="bi bi-check-circle-fill"></i> Claim Management
            </a>
            <a href="<?= site_url('reports') ?>" class="<?= (strpos(uri_string(), 'reports') === 0) ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-fill"></i> Reports & Analytics
            </a>
            <a href="<?= site_url('users') ?>" class="<?= (strpos(uri_string(), 'users') === 0) ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> User Management
            </a>
            <a href="<?= site_url('admin_role') ?>" class="<?= (strpos(uri_string(), 'admin_role') === 0) ? 'active' : '' ?>">
                <i class="bi bi-person-badge-fill"></i> Role Management
            </a>
        </nav>
        <div class="sidebar-footer">
            <?php
                $adminRoleModel = new \App\Models\AdminRoleModel();
                $session_email = session()->get('email');
                $user_role = $session_email ? $adminRoleModel->where('email', $session_email)->first() : null;
                $profile_link = $user_role ? site_url('admin_role/view/' . $user_role['id']) : '#';
            ?>
            <div class="user-box">
                <a href="<?= $profile_link ?>" class="user-profile-link">
                    <div class="user-profile">
                        <img src="<?= session()->get('profile_pic') ?>" alt="User" referrerpolicy="no-referrer">
                        <div class="user-info">
                            <p><?= session()->get('name') ?></p>
                            <small><?= session()->get('email') ?></small>
                        </div>
                    </div>
                </a>
                <a href="/logout" class="logout-button">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')):
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
