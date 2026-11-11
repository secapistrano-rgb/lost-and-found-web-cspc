<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title><?= $this->renderSection('title') ?> - CSPC Lost & Found</title>
    
    <!-- IMMEDIATE Session Check - Runs BEFORE page renders -->
    <script>
        // Check if logged out (immediate check before anything loads)
        (function() {
            // Prevent back button by pushing forward
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        })();
    </script>
    <!-- Google Fonts for Professional Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cspc-primary': '#003366',
                        'cspc-secondary': '#004d99',
                        'cspc-accent': '#0066cc',
                        'cspc-dark': '#002040',
                        'cspc-light': '#f0f2f5',
                        'glass': 'rgba(255, 255, 255, 0.1)',
                        'glass-dark': 'rgba(0, 0, 0, 0.1)',
                        'primary': {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        'secondary': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        },
                        'accent': {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                            950: '#422006',
                        },
                        'success': {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        'warning': {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                            950: '#451a03',
                        },
                        'danger': {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                            950: '#450a0a',
                        },
                        'info': {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        'neutral': {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                            950: '#0a0a0a',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'display': ['Poppins', 'Inter', 'system-ui', 'sans-serif']
                    },
                    fontSize: {
                        'xs': ['0.75rem', { lineHeight: '1.5', letterSpacing: '0.025em' }],
                        'sm': ['0.875rem', { lineHeight: '1.5', letterSpacing: '0.025em' }],
                        'base': ['1rem', { lineHeight: '1.6', letterSpacing: '0.01em' }],
                        'lg': ['1.125rem', { lineHeight: '1.6', letterSpacing: '0.01em' }],
                        'xl': ['1.25rem', { lineHeight: '1.5', letterSpacing: '0.01em' }],
                        '2xl': ['1.5rem', { lineHeight: '1.4', letterSpacing: '-0.01em' }],
                        '3xl': ['1.875rem', { lineHeight: '1.3', letterSpacing: '-0.02em' }],
                        '4xl': ['2.25rem', { lineHeight: '1.2', letterSpacing: '-0.02em' }],
                        '5xl': ['3rem', { lineHeight: '1.1', letterSpacing: '-0.03em' }],
                        '6xl': ['3.75rem', { lineHeight: '1', letterSpacing: '-0.03em' }]
                    },
                    spacing: {
                        '0.5': '0.125rem',
                        '1.5': '0.375rem',
                        '2.5': '0.625rem',
                        '3.5': '0.875rem',
                        '4.5': '1.125rem',
                        '5.5': '1.375rem',
                        '6.5': '1.625rem',
                        '7.5': '1.875rem',
                        '8.5': '2.125rem',
                        '9.5': '2.375rem',
                        '10.5': '2.625rem',
                        '11.5': '2.875rem',
                        '12.5': '3.125rem',
                        '13': '3.25rem',
                        '15': '3.75rem',
                        '17': '4.25rem',
                        '18': '4.5rem',
                        '19': '4.75rem',
                        '21': '5.25rem',
                        '22': '5.5rem',
                        '23': '5.75rem',
                        '25': '6.25rem',
                        '26': '6.5rem',
                        '27': '6.75rem',
                        '28': '7rem',
                        '29': '7.25rem',
                        '30': '7.5rem',
                        '32': '8rem',
                        '36': '9rem',
                        '40': '10rem',
                        '44': '11rem',
                        '48': '12rem',
                        '52': '13rem',
                        '56': '14rem',
                        '60': '15rem',
                        '64': '16rem',
                        '68': '17rem',
                        '72': '18rem',
                        '76': '19rem',
                        '80': '20rem',
                        '84': '21rem',
                        '88': '22rem',
                        '92': '23rem',
                        '96': '24rem',
                        '100': '25rem',
                        '104': '26rem',
                        '108': '27rem',
                        '112': '28rem',
                        '116': '29rem',
                        '120': '30rem',
                        '128': '32rem',
                        '144': '36rem',
                        '160': '40rem',
                        '176': '44rem',
                        '192': '48rem',
                        '208': '52rem',
                        '224': '56rem',
                        '240': '60rem',
                        '256': '64rem',
                        '288': '72rem',
                        '320': '80rem',
                        '384': '96rem'
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --cspc-primary: #003366;
            --cspc-secondary: #004d99;
            --cspc-accent: #0066cc;
            --cspc-dark: #002040;
            --cspc-light: #f0f2f5;
            
            /* Theme Variables */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --border-primary: #e2e8f0;
            --border-secondary: #cbd5e1;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            --shadow-heavy: rgba(0, 0, 0, 0.25);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.25);
            --glass-shadow: rgba(0, 0, 0, 0.1);
        }
        
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-feature-settings: 'kern' 1, 'liga' 1, 'calt' 1;
            text-rendering: optimizeLegibility;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Typography Hierarchy */
        .text-display {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.1;
        }
        
        .text-heading {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        
        .text-subheading {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            letter-spacing: -0.01em;
            line-height: 1.4;
        }
        
        .text-body {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            letter-spacing: 0.01em;
            line-height: 1.6;
        }
        
        .text-caption {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            letter-spacing: 0.025em;
            line-height: 1.5;
            text-transform: uppercase;
        }
        
        /* Advanced Glassmorphism Effects with Dark Mode Support */
        .glass {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px var(--glass-shadow);
            transition: all 0.3s ease;
        }
        
        .glass-dark {
            backdrop-filter: blur(20px) saturate(200%);
            -webkit-backdrop-filter: blur(20px) saturate(200%);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 12px 40px var(--shadow-medium);
            transition: all 0.3s ease;
        }
        
        .glass-card {
            backdrop-filter: blur(25px) saturate(150%);
            -webkit-backdrop-filter: blur(25px) saturate(150%);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 16px 48px var(--shadow-medium), inset 0 1px 0 var(--glass-border);
            transition: all 0.3s ease;
        }
        
        .glass-button {
            backdrop-filter: blur(12px) saturate(120%);
            -webkit-backdrop-filter: blur(12px) saturate(120%);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 16px var(--shadow-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-button:hover {
            backdrop-filter: blur(16px) saturate(140%);
            -webkit-backdrop-filter: blur(16px) saturate(140%);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 24px var(--shadow-medium);
            transform: translateY(-2px);
        }
        
        /* Scrollbar Styling with Dark Mode Support */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: var(--text-tertiary) transparent;
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
            border-radius: 10px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: var(--text-tertiary);
            border-radius: 10px;
            border: 1px solid var(--border-secondary);
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
        
        /* Enhanced Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 30px rgba(59, 130, 246, 0.6); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }
        
        .animate-slide-out-right {
            animation: slideOutRight 0.3s ease-in;
        }
        
        /* Consistent Spacing System */
        .section-spacing {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .section-spacing-lg {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        
        .card-spacing {
            padding: 1.5rem;
        }
        
        .card-spacing-lg {
            padding: 2rem;
        }
        
        .element-spacing {
            margin-bottom: 1rem;
        }
        
        .element-spacing-lg {
            margin-bottom: 1.5rem;
        }
        
        /* Layout Composition Utilities */
        .layout-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .layout-grid-lg {
            display: grid;
            gap: 2rem;
        }
        
        .layout-flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .layout-flex-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .layout-flex-start {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        
        .layout-flex-end {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        
        /* Responsive Container */
        .container-fluid-responsive {
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        @media (min-width: 576px) {
            .container-fluid-responsive {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .container-fluid-responsive {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        
        @media (min-width: 992px) {
            .container-fluid-responsive {
                padding-left: 2.5rem;
                padding-right: 2.5rem;
            }
        }
        
        @media (min-width: 1200px) {
            .container-fluid-responsive {
                padding-left: 3rem;
                padding-right: 3rem;
            }
        }
        

        
        /* Access Denied Styles */
        .sidebar-nav a.access-denied {
            color: #6c757d !important;
            cursor: not-allowed;
            opacity: 0.6;
            position: relative;
        }
        
        .sidebar-nav a.access-denied:hover {
            background-color: #f8f9fa;
            color: #6c757d !important;
        }
        
        .access-denied-badge {
            background-color: #dc3545;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
            font-weight: 500;
        }
        
        .sidebar-nav a.access-denied .access-denied-badge {
            display: inline-block;
        }
        
        /* Toast notification styles */
        .access-denied-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease-out;
        }
        
        .access-denied-toast.fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }
        
        /* Utility classes */
        .bg-primary {
            background-color: var(--bg-primary);
        }
        
        .bg-secondary {
            background-color: var(--bg-secondary);
        }
        
        .bg-tertiary {
            background-color: var(--bg-tertiary);
        }
        
        .text-primary {
            color: var(--text-primary);
        }
        
        .text-secondary {
            color: var(--text-secondary);
        }
        
        .text-tertiary {
            color: var(--text-tertiary);
        }
        
        .border-primary {
            border-color: var(--border-primary);
        }
        
        .border-secondary {
            border-color: var(--border-secondary);
        }
        
        /* Card and component styling */
        .card-custom {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-primary);
            box-shadow: 0 4px 16px var(--shadow-light);
            transition: all 0.3s ease;
        }
        
        .card-custom:hover {
            box-shadow: 0 8px 24px var(--shadow-medium);
        }
        
        /* Improved Grid System */
        .grid-auto-fit {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .grid-auto-fill {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.25rem;
        }
        
        /* Enhanced Card Layouts */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            align-items: start;
        }
        
        .card-grid-sm {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: start;
        }
        
        .card-grid-lg {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            align-items: start;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        .animate-shimmer {
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
                background-size: 200% 100%;
                animation: shimmer 2s infinite;
            }
            
            /* Semantic Color Utilities */
            .text-success { color: #10b981; }
            .text-warning { color: #f59e0b; }
            .text-danger { color: #ef4444; }
            .text-info { color: #0ea5e9; }
            
            .bg-success { background-color: #10b981; }
            .bg-warning { background-color: #f59e0b; }
            .bg-danger { background-color: #ef4444; }
            .bg-info { background-color: #0ea5e9; }
            
            .border-success { border-color: #10b981; }
            .border-warning { border-color: #f59e0b; }
            .border-danger { border-color: #ef4444; }
            .border-info { border-color: #0ea5e9; }
            
            /* High Contrast Mode Support */
            @media (prefers-contrast: high) {
                .glass, .glass-dark, .glass-card, .glass-button {
                    border-width: 2px;
                    border-color: rgba(0, 0, 0, 0.8);
                }
                
                .text-gray-500 { color: #374151; }
                .text-gray-400 { color: #4b5563; }
                .text-gray-300 { color: #6b7280; }
            }
            
            /* Reduced Motion Support */
            @media (prefers-reduced-motion: reduce) {
                .animate-float,
                .animate-glow,
                .animate-shimmer,
                .animate-pulse {
                    animation: none;
                }
                
                .transition-all,
                .transition-transform,
                .transition-colors,
                .transition-opacity {
                    transition: none;
                }
            }
            
            /* Focus Visible Improvements */
            .focus-visible:focus-visible {
                outline: 2px solid #3b82f6;
                outline-offset: 2px;
                border-radius: 0.375rem;
            }
            
            button:focus-visible,
            a:focus-visible {
                outline: 2px solid #3b82f6;
                outline-offset: 2px;
                border-radius: 0.375rem;
            }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, rgba(2, 52, 85, 0.7) 0%, rgba(10, 76, 120, 0.8) 100%), url('/images/logo-background.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            backdrop-filter: blur(25px);
            padding: 0;
            z-index: 1000;
            box-shadow: 0 15px 50px rgba(0,0,0,0.15), 0 5px 20px rgba(52, 152, 219, 0.25);
            border: 1px solid rgba(255,255,255,0.3);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            border-radius: 0;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Hamburger Menu Styles */
        .hamburger-menu {
            display: none;
            flex-direction: column;
            justify-content: space-around;
            width: 40px;
            height: 40px;
            background: #003366;
            border: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            padding: 8px;
            margin-right: 1rem;
            z-index: 1001;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .hamburger-menu:hover {
            background: #004d99;
            transform: scale(1.05);
        }
        
        .hamburger-line {
            width: 100%;
            height: 3px;
            background-color: #365c8b;
            border-radius: 2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        
        .hamburger-menu.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .hamburger-menu.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger-menu.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .hamburger-menu {
                display: flex;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .nav-panel {
                margin-left: 0;
            }
            
            .welcome-message {
                margin-left: 0.5rem;
            }
            
            .welcome-text {
                font-size: 0.95rem;
            }
            
            .current-time {
                font-size: 0.7rem;
            }
            
            .nav-panel-content {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .nav-panel-left {
                flex: 1;
                min-width: 200px;
            }
        }
        
        @media (min-width: 769px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .hamburger-menu {
                display: none !important;
            }
        }
        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            margin-bottom: 0;
            text-align: center;
            background: rgba(255,255,255,0.2);
            border-radius: 0;
            flex-shrink: 0;
        }
        .sidebar-header img {
            width: 70px; 
            height: 70px; 
            margin: 0 auto;
            display: block;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.3);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            background: #fff;
            padding: 6px;
        }
        .sidebar-header img:hover {
            transform: scale(1.05);
        }
        .sidebar-header h3 {
            color: #ffffff;
            margin: 1rem 0 0;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-align: center;
            line-height: 1.2;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0.5rem 0;
            margin: 0 0 1rem 0;
            flex-grow: 1;
            overflow: visible;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 0.15rem;
        }
        .sidebar-nav a {
            color: #ffffff;
            text-decoration: none;
            padding: 0.5rem 1.5rem;
            display: flex;
            align-items: center;
            border-radius: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 1rem;
            background: transparent;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            border-radius: 12px;
            min-height: 42px;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a:active,
        .sidebar-nav a.active {
            color: #ffffff !important;
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.3);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .sidebar-nav i {
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding: 1rem;
            margin-top: 1rem;
            background: rgba(3, 21, 81, 0.8);
            border-radius: 0;
            flex-shrink: 0;
            backdrop-filter: blur(10px);
        }
        .user-box {
            background: transparent;
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
        }
        .user-profile-link {
            text-decoration: none;
            display: block;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .user-profile-link:hover .user-profile {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-1px);
        }
        .user-profile {
            display: flex;
            align-items: center;
            color: white;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .user-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .user-info {
            flex: 1;
            min-width: 0; /* Prevents flex item from overflowing its container */
        }
        .user-info p {
            color: white;
            font-weight: 700;
            margin: 0 0 2px 0;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .user-info small {
            color: rgba(255,255,255,0.8);
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            margin-bottom: 4px;
        }
        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        .role-badge.super-admin {
            background: #45a049;
            color: white;
            box-shadow: 0 1px 3px rgba(69, 160, 73, 0.4);
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
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 8px 12px;
            text-align: center;
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid rgba(220, 53, 69, 0.2);
            margin-top: 4px;
        }
        .logout-button:hover {
            background: rgba(220, 53, 69, 0.2);
            color: #ff5252;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.25);
            text-decoration: none;
        }
        .logout-button i {
            margin-right: 6px;
            font-size: 0.75rem;
        }
        /* Navigation Panel */
        .nav-panel {
            margin-left: 280px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-panel-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
        }
        .nav-panel-left .page-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a202c;
        }
        .welcome-message {
            display: flex;
            flex-direction: column;
            margin-left: 1rem;
        }
        .welcome-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--cspc-primary);
            margin: 0;
            line-height: 1.2;
        }
        .current-time {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
            font-weight: 400;
        }
        .nav-panel-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .nav-item {
            position: relative;
        }
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            color: #4a5568;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .nav-btn:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }
        .nav-btn i {
            font-size: 1rem;
            color: #6b7280;
        }
        .nav-btn .nav-label {
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        .notification-badge {
            background: #dc2626;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            min-width: 1rem;
            text-align: center;
            line-height: 1;
        }

        .nav-label {
            white-space: nowrap;
        }

        .main-content {
            margin-left: 280px;
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
    <?= $this->renderSection('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/management.css') ?>">
</head>
<body>
    <aside class="sidebar" id="app-sidebar" aria-label="Workspace navigation">
        <div class="sidebar-header">
            <img src="<?= base_url('images/logo.png') ?>" alt="CSPC Logo">
            <h3>Lost &amp; Found<br><span>Management workspace</span></h3>
            <button type="button" class="app-sidebar-close" aria-label="Close navigation"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= site_url('dashboard') ?>" class="<?= isActiveModule('dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            
            <?php 
            // Prefer session role first; fallback to DB, then default to staff
            $userRole = session()->get('user_role') ?: null;
            $adminRoleModel = new \App\Models\AdminRoleModel();
            $session_email = session()->get('email');
            $user_role_data = $session_email ? $adminRoleModel->where('email', $session_email)->first() : null;
            if (!$userRole && $user_role_data) {
                $userRole = $user_role_data['role'] ?? null;
            }
            $userRole = str_replace(' ', '_', strtolower(trim($userRole ?? 'staff')));
            
            // Helper function to check if current URI matches a module
            function isActiveModule($module) {
                $currentUri = uri_string();
                return strpos($currentUri, $module) === 0 || 
                       (empty($currentUri) && $module === 'dashboard') ||
                       ($currentUri === 'dashboard' && $module === 'dashboard');
            }
            ?>
            
            <?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
            <a href="<?= site_url('items') ?>" class="<?= isActiveModule('items') ? 'active' : '' ?>">
                <i class="bi bi-box-seam-fill"></i> Item Management
            </a>
            <?php endif; ?>
            
            <?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
            <a href="<?= site_url('claims') ?>" class="<?= isActiveModule('claims') ? 'active' : '' ?>">
                <i class="bi bi-check-circle-fill"></i> Claim Management
            </a>
            <?php endif; ?>
            
            <?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
            <a href="<?= site_url('match') ?>" class="<?= isActiveModule('match') ? 'active' : '' ?>">
                <i class="bi bi-arrow-left-right"></i> Match Management
            </a>
            <?php endif; ?>
            
            <!-- Email Management - Super Admin and Admin only -->
            <?php if (in_array($userRole, ['super_admin', 'admin'])): ?>
            <a href="<?= site_url('emails') ?>" class="<?= isActiveModule('emails') ? 'active' : '' ?>">
                <i class="bi bi-envelope-fill"></i> Email Management
            </a>
            <?php else: ?>
            <a href="#" class="access-denied" onclick="return showAccessDenied('Email Management')">
                <i class="bi bi-envelope-fill"></i> Email Management
                <span class="access-denied-badge">Access Denied</span>
            </a>
            <?php endif; ?>
            
            <!-- Reports & Analytics - Super Admin and Admin only -->
            <?php if (in_array($userRole, ['super_admin', 'admin'])): ?>
            <a href="<?= site_url('reports') ?>" class="<?= isActiveModule('reports') ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-fill"></i> Reports & Analytics
            </a>
            <?php else: ?>
            <a href="#" class="access-denied" onclick="return showAccessDenied('Reports & Analytics')">
                <i class="bi bi-bar-chart-fill"></i> Reports & Analytics
                <span class="access-denied-badge">Access Denied</span>
            </a>
            <?php endif; ?>
            
            <!-- User Management - Super Admin and Admin only -->
            <?php if (in_array($userRole, ['super_admin', 'admin'])): ?>
            <a href="<?= site_url('users') ?>" class="<?= isActiveModule('users') ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> User Management
            </a>
            <?php else: ?>
            <a href="#" class="access-denied" onclick="return showAccessDenied('User Management')">
                <i class="bi bi-people-fill"></i> User Management
                <span class="access-denied-badge">Access Denied</span>
            </a>
            <?php endif; ?>
            
            <!-- Role Management - Super Admin and Admin only -->
            <?php if (in_array($userRole, ['super_admin', 'admin'])): ?>
            <a href="<?= site_url('admin_role') ?>" class="<?= isActiveModule('admin_role') ? 'active' : '' ?>">
                <i class="bi bi-person-badge-fill"></i> Role Management
            </a>
            <?php else: ?>
            <a href="#" class="access-denied" onclick="return showAccessDenied('Role Management')">
                <i class="bi bi-person-badge-fill"></i> Role Management
                <span class="access-denied-badge">Access Denied</span>
            </a>
            <?php endif; ?>
            
            <!-- Feedback Management - Super Admin, Admin, and Staff -->
            <?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
            <a href="<?= site_url('feedback') ?>" class="<?= isActiveModule('feedback') ? 'active' : '' ?>">
                <i class="bi bi-chat-square-text-fill"></i> Feedback Management
            </a>
            <?php else: ?>
            <a href="#" class="access-denied" onclick="return showAccessDenied('Feedback Management')">
                <i class="bi bi-chat-square-text-fill"></i> Feedback Management
                <span class="access-denied-badge">Access Denied</span>
            </a>
            <?php endif; ?>
            
            <!-- Help & Support - Super Admin, Admin, and Staff -->
            <?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
            <a href="<?= site_url('help-support') ?>" class="<?= isActiveModule('help-support') ? 'active' : '' ?>">
                <i class="bi bi-headset"></i> Help & Support
            </a>
            <?php else: ?>
            <a href="#" class="access-denied" onclick="return showAccessDenied('Help & Support')">
                <i class="bi bi-headset"></i> Help & Support
                <span class="access-denied-badge">Access Denied</span>
            </a>
            <?php endif; ?>
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
                        <?php if (session()->get('profile_pic')): ?>
                            <img src="<?= session()->get('profile_pic') ?>" 
                                 alt="User" 
                                 referrerpolicy="no-referrer"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 onload="this.style.display='block'; this.nextElementSibling.style.display='none';">
                            <div class="default-avatar" style="width: 32px; height: 32px; border-radius: 50%; background-color: #6c757d; display: none; align-items: center; justify-content: center; margin-right: 10px; border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                <i class="bi bi-person-fill" style="color: white; font-size: 1rem;"></i>
                            </div>
                        <?php else: ?>
                            <div class="default-avatar" style="width: 32px; height: 32px; border-radius: 50%; background-color: #6c757d; display: flex; align-items: center; justify-content: center; margin-right: 10px; border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                <i class="bi bi-person-fill" style="color: white; font-size: 1rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="user-info">
                            <p><?= session()->get('name') ?></p>
                            <small><?= session()->get('email') ?></small>
                            <?php 
                                $roleClass = str_replace(['_', ' '], '-', strtolower($user_role['role'] ?? 'user'));
                                $roleDisplay = ucwords(str_replace('_', ' ', $user_role['role'] ?? 'user'));
                            ?>
                            <span class="role-badge <?= $roleClass ?>"><?= $roleDisplay ?></span>
                        </div>
                    </div>
                </a>
                <a href="/logout" class="logout-button">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </aside>
    <button type="button" class="app-sidebar-backdrop" aria-label="Close navigation" hidden></button>

    <!-- Navigation Panel -->
    <div class="nav-panel">
        <div class="nav-panel-content">
            <div class="nav-panel-left">
                <button class="hamburger-menu" id="hamburgerMenu" aria-label="Open navigation" aria-controls="app-sidebar" aria-expanded="false">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                <div class="welcome-message">
                    <span class="welcome-text">Welcome back, <?= session()->get('name') ?? 'User' ?>!</span>
                    <small class="current-time" id="currentTime"></small>
                </div>
                <h2 class="page-title"></h2>
            </div>
            <div class="nav-panel-right">
                <div class="nav-item">
                    <button class="nav-btn notification-btn" id="notificationsBtn" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="nav-label">Notifications</span>
                        <span class="notification-badge hidden">0</span>
                    </button>
                </div>
                <div class="nav-item">
                    <button class="nav-btn" id="activityBtn" aria-label="Activity logs">
                        <i class="fas fa-history"></i>
                        <span class="nav-label">Activity Logs</span>
                        <span class="notification-badge activity-count-badge" id="activityCountBadge">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content mg-workspace">
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
    <script>

        // Hamburger menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation behavior is shared in management.js.

            // Update current time display
            function updateCurrentTime() {
                const timeElement = document.getElementById('currentTime');
                if (timeElement) {
                    const now = new Date();
                    const options = {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    timeElement.textContent = now.toLocaleDateString('en-US', options);
                }
            }
            
            // Update time immediately and then every minute
            updateCurrentTime();
            setInterval(updateCurrentTime, 60000);
        });
        
        // Activity Logs button click handler
        document.addEventListener('DOMContentLoaded', function() {
            const activityBtn = document.getElementById('activityBtn');
            if (activityBtn) {
                activityBtn.addEventListener('click', function() {
                    window.location.href = '<?= site_url('activity-logs') ?>';
                });
            }
            
            // Update activity count badge
            updateActivityCount();
            
            // Update activity count every 30 seconds
            setInterval(updateActivityCount, 30000);
        });
        
        // Function to update activity count badge
        function updateActivityCount() {
            const activityCountBadge = document.getElementById('activityCountBadge');
            if (!activityCountBadge) return;
            
            fetch('<?= site_url('activity-logs/count') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const count = data.count;
                        activityCountBadge.textContent = count > 99 ? '99+' : count;
                        
                        if (count > 0) {
                            activityCountBadge.classList.remove('hidden');
                        } else {
                            activityCountBadge.classList.add('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching activity count:', error);
                });
        }

        // Function to show access denied message
        function showAccessDenied(moduleName) {
            // Remove any existing toast
            const existingToast = document.querySelector('.access-denied-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create new toast
            const toast = document.createElement('div');
            toast.className = 'access-denied-toast';
            toast.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill"></i>
                Access Denied: You don't have permission to access ${moduleName}
            `;
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
            
            return false;
        }
    </script>
    
    <!-- Notification System JavaScript -->
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    
    <!-- Prevent Back Button Cache Access After Logout -->
    <script>
        (function() {
            'use strict';
            
            // AGGRESSIVE: Check session on EVERY page load (including back button)
            function checkSessionValidity() {
                fetch('<?= base_url('check-session') ?>', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    },
                    cache: 'no-store'
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.logged_in) {
                        // Session invalid - redirect to login immediately
                        window.location.replace('<?= base_url('login') ?>');
                    }
                })
                .catch(() => {
                    // On error, redirect to login for security
                    window.location.replace('<?= base_url('login') ?>');
                });
            }
            
            // Run immediately on page load
            checkSessionValidity();
            
            // Disable browser back button cache completely
            window.history.forward();
            function noBack() {
                window.history.forward();
            }
            
            // Check on visibility change (when tab becomes active)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkSessionValidity();
                }
            });
            
            // Handle page show event (back/forward navigation)
            window.addEventListener('pageshow', function(event) {
                // ALWAYS check session when page is shown, even from cache
                checkSessionValidity();
                
                // If page came from cache, force reload
                if (event.persisted || 
                    (performance && performance.getEntriesByType && 
                     performance.getEntriesByType('navigation')[0]?.type === 'back_forward')) {
                    checkSessionValidity();
                }
            });
            
            // Disable bfcache (back-forward cache)
            window.addEventListener('unload', function() {
                // This prevents the page from being cached
            });
            
            // Additional check on focus
            window.addEventListener('focus', function() {
                checkSessionValidity();
            });
            
        })();
    </script>
    
    <?= $this->renderSection('scripts') ?>
    <script src="<?= base_url('assets/js/management.js') ?>"></script>
</body>
</html>
