<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
    <meta name="description" content="CSPC Lost & Found Management System - Efficiently manage lost and found items" />
    <meta name="theme-color" content="#003366" />
    <title><?= $this->renderSection('title') ?> - CSPC Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
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
            width: 280px;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.9) 50%, rgba(0, 51, 102, 0.9) 100%), url('<?= base_url('images/school-background.jpg') ?>');
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(10px);
            padding: 0;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }
        
        /* Mobile sidebar behavior */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
        }
        .sidebar-header {
            padding: 2rem 1.5rem 1.5rem;
            margin-bottom: 0;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            text-align: center;
            background: rgba(255,255,255,0.05);
        }
        .sidebar-header img {
            width: 70px; 
            height: 70px; 
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }
        .sidebar-header img:hover {
            transform: scale(1.05);
        }
        .sidebar-header h3 {
            color: #fff;
            margin: 1rem 0 0;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .sidebar-nav {
            list-style: none;
            padding: 1.5rem 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        .sidebar-nav li {
            margin: 0;
        }
        .sidebar-nav a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            border-radius: 0;
            transition: all 0.3s;
            margin: 0;
            background: transparent;
            border-left: 3px solid transparent;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
        }
        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #00d4ff;
            transform: translateX(5px);
        }
        .sidebar-nav a.active {
            background: linear-gradient(90deg, rgba(0, 212, 255, 0.2), rgba(0, 212, 255, 0.1));
            color: #fff;
            border-left-color: #00d4ff;
            font-weight: 600;
        }
        .sidebar-nav i {
            margin-right: 1rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.15);
            padding: 1.5rem;
            margin-top: 0;
            background: rgba(0,0,0,0.1);
        }
        .user-box {
            background: rgba(255,255,255,0.08);
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }
        .user-profile {
            display: flex;
            align-items: center;
            color: #fff;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 0.75rem;
            transition: background-color 0.3s ease;
        }
        .user-profile img,
        .default-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .user-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .user-info p {
            margin: 0 0 0.25rem 0;
            font-size: 0.95rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .user-info small {
            margin: 0 0 0.5rem 0;
            font-size: 0.8rem;
            opacity: 0.8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .role-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 0.4rem;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            align-self: flex-start;
        }
        .role-badge.super-admin {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            box-shadow: 0 2px 4px rgba(30, 58, 138, 0.3);
        }
        .role-badge.admin {
            background: linear-gradient(135deg, #1e40af, #60a5fa);
            color: white;
            box-shadow: 0 2px 4px rgba(30, 64, 175, 0.3);
        }
        .role-badge.staff {
            background: linear-gradient(135deg, #2563eb, #93c5fd);
            color: white;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
        }
        .role-badge.user {
            background: linear-gradient(135deg, #3b82f6, #bfdbfe);
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }
        .logout-button {
            display: block;
            width: 100%;
            padding: 0.75rem;
            text-align: center;
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.3));
            color: #fff;
            text-decoration: none;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        .logout-button:hover {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.4), rgba(220, 53, 69, 0.5));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: margin-left 0.3s ease, padding 0.3s ease;
            min-height: 100vh;
        }
        
        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 9999;
            background: var(--cspc-primary, #003366);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            display: block !important;
            width: 50px;
            height: 50px;
        }
        
        .sidebar-toggle:hover {
            background: var(--cspc-secondary, #004d99);
            transform: scale(1.05);
        }
        
        /* Hamburger Lines */
        .hamburger-line {
            display: block;
            width: 20px;
            height: 2px;
            background: white;
            margin: 3px auto;
            transition: all 0.3s ease;
            border-radius: 1px;
        }
        
        .sidebar-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .sidebar-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .sidebar-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        /* Sidebar behavior for all screen sizes */
        .sidebar.show {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
            padding: 1rem;
            padding-top: 4rem;
        }
        
        /* Mobile-specific adjustments */
        @media (max-width: 767.98px) {
            .main-content {
                padding: 1rem;
                padding-top: 4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('images/logo.png') ?>" alt="CSPC Logo">
            <h3>Lost & Found Management System</h3>
        </div>
        
        <?php
            // ✅ Unified Role Handling
            $adminRoleModel = new \App\Models\AdminRoleModel();
            $session_email = session()->get('email');
            $userRoleData = $session_email ? $adminRoleModel->where('email', $session_email)->first() : null;

            // Prefer session role to avoid DB lag/mismatch; fallback to DB; default staff
            $userRole = session()->get('user_role') ?? ($userRoleData['role'] ?? 'staff');
            $userRole = str_replace(' ', '_', strtolower(trim($userRole))); // normalize and trim
            $currentUri = uri_string();
            
            // Helper function to check if current URI matches a module
            function isActiveModule($module) {
                $currentUri = uri_string();
                return strpos($currentUri, $module) === 0 || 
                       (empty($currentUri) && $module === 'dashboard') ||
                       ($currentUri === 'dashboard' && $module === 'dashboard');
            }
        ?>

        <ul class="sidebar-nav">
            <li>
                <a href="<?= site_url('dashboard') ?>" class="<?= isActiveModule('dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="<?= site_url('items') ?>" class="<?= isActiveModule('items') ? 'active' : '' ?>">
                    <i class="bi bi-box"></i> Item Management
                    <?php if ($userRole === 'staff'): ?>
                        <small style="margin-left: auto; font-size: 0.7rem; opacity: 0.7;">(View Only)</small>
                    <?php endif; ?>
                </a>
            </li>

            <li>
                <a href="<?= site_url('claims') ?>" class="<?= isActiveModule('claims') ? 'active' : '' ?>">
                    <i class="bi bi-clipboard-check"></i> Claim Management
                </a>
            </li>

            <li>
                <a href="<?= site_url('match') ?>" class="<?= isActiveModule('match') ? 'active' : '' ?>">
                    <i class="bi bi-arrow-left-right"></i> Match Management
                </a>
            </li>

            <li>
                <a href="<?= site_url('emails') ?>" class="<?= isActiveModule('emails') ? 'active' : '' ?>">
                    <i class="bi bi-envelope"></i> Email Management
                </a>
            </li>

            <?php if (in_array($userRole, ['super_admin', 'admin'])): ?>
            <li>
                <a href="<?= site_url('reports') ?>" class="<?= isActiveModule('reports') ? 'active' : '' ?>">
                    <i class="bi bi-bar-chart"></i> Reports & Analytics
                </a>
            </li>
            <li>
                <a href="<?= site_url('users') ?>" class="<?= isActiveModule('users') ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> User Management
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin_role') ?>" class="<?= isActiveModule('admin_role') ? 'active' : '' ?>">
                    <i class="bi bi-shield-check"></i> Role Management
                </a>
            </li>
            <?php endif; ?>

            <?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
            <li>
                <a href="<?= site_url('feedback') ?>" class="<?= isActiveModule('feedback') ? 'active' : '' ?>">
                    <i class="bi bi-chat-dots"></i> Feedback Management
                </a>
            </li>
            <li>
                <a href="<?= site_url('help-support') ?>" class="<?= isActiveModule('help-support') ? 'active' : '' ?>">
                    <i class="bi bi-question-circle"></i> Help & Support
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <div class="sidebar-footer">
            <div class="user-box">
                <div class="user-profile">
                    <?php if (session()->get('profile_pic')): ?>
                        <img src="<?= session()->get('profile_pic') ?>" alt="User" referrerpolicy="no-referrer">
                    <?php else: ?>
                        <div class="default-avatar" style="width: 45px; height: 45px; border-radius: 50%; background-color: #6c757d; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="bi bi-person-fill" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="user-info">
                        <p><?= session()->get('name') ?></p>
                        <small><?= session()->get('email') ?></small>
                        <?php 
                            $roleClass = str_replace(['_', ' '], '-', strtolower($userRoleData['role'] ?? $userRole));
                            $roleDisplay = ucwords(str_replace('_', ' ', $userRoleData['role'] ?? $userRole));
                        ?>
                        <span class="role-badge <?= $roleClass ?>"><?= $roleDisplay ?></span>
                    </div>
                </div>
                <a href="<?= site_url('logout') ?>" class="logout-button">
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
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preserve sidebar scroll position and handle mobile navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar-nav');
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            const sidebarElement = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Restore scroll position on page load
            const savedScrollPosition = sessionStorage.getItem('sidebarScrollPosition');
            if (savedScrollPosition && sidebar) {
                sidebar.scrollTop = parseInt(savedScrollPosition);
            }
            
            // Save scroll position before navigation
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (sidebar) {
                        sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                    }
                    // Close mobile sidebar on navigation
                    if (window.innerWidth <= 767.98) {
                        closeMobileSidebar();
                    }
                });
            });
            
            // Save scroll position on scroll
            if (sidebar) {
                sidebar.addEventListener('scroll', function() {
                    sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                });
            }
            
            // Mobile sidebar functionality
            function openMobileSidebar() {
                if (sidebarElement) sidebarElement.classList.add('show');
                if (sidebarOverlay) sidebarOverlay.classList.add('show');
                if (sidebarToggle) sidebarToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMobileSidebar() {
                if (sidebarElement) sidebarElement.classList.remove('show');
                if (sidebarOverlay) sidebarOverlay.classList.remove('show');
                if (sidebarToggle) sidebarToggle.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Toggle button event
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (sidebarElement && sidebarElement.classList.contains('show')) {
                        closeMobileSidebar();
                    } else {
                        openMobileSidebar();
                    }
                });
            }
            
            // Overlay click to close
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeMobileSidebar);
            }
            
            // Close sidebar on window resize if desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 767.98) {
                    closeMobileSidebar();
                }
            });
            
            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebarElement && sidebarElement.classList.contains('show')) {
                    closeMobileSidebar();
                }
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
