<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading - CSPC Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cspc-primary: #003366;
            --cspc-secondary: #004d99;
        }
        
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--cspc-primary) 0%, var(--cspc-secondary) 100%);
            overflow: hidden;
        }
        
        .loading-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: white;
        }
        
        .logo-container {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background-color: white;
            padding: 10px;
            animation: logoFloat 2s ease-in-out infinite alternate;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            object-fit: cover;
            object-position: center;
        }
        
        .loading-ring {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 140px;
            height: 140px;
            border: 3px solid transparent;
            border-top: 3px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }
        
        .loading-ring::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            width: 140px;
            height: 140px;
            border: 3px solid transparent;
            border-bottom: 3px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            animation: spin 2s linear infinite reverse;
        }
        
        .loading-text {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            animation: textPulse 2s ease-in-out infinite;
        }
        
        .loading-subtitle {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }
        
        .progress-container {
            width: 300px;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 1));
            border-radius: 2px;
            animation: progressLoad 3s ease-in-out;
            transform-origin: left;
        }
        
        .loading-dots {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .dot {
            width: 8px;
            height: 8px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            animation: dotBounce 1.4s ease-in-out infinite both;
        }
        
        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }
        .dot:nth-child(3) { animation-delay: 0s; }
        
        @keyframes logoFloat {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-10px) scale(1.05); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes textPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @keyframes progressLoad {
            0% { transform: scaleX(0); }
            100% { transform: scaleX(1); }
        }
        
        @keyframes dotBounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
        
        .fade-out {
            animation: fadeOut 0.5s ease-in-out forwards;
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="loading-container" id="loadingContainer">
        <div class="logo-container">
            <div class="loading-ring"></div>
            <img src="<?= base_url('images/logo.png') ?>" alt="CSPC Logo" class="logo">
        </div>
        
        <div class="loading-text">Welcome to CSPC Lost & Found</div>
        <div class="loading-subtitle">Setting up your dashboard...</div>
        
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
        
        <div class="loading-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <script>
        // Simulate loading process
        setTimeout(function() {
            document.getElementById('loadingContainer').classList.add('fade-out');
            
            // Redirect to dashboard after fade out animation
            setTimeout(function() {
                window.location.href = '<?= base_url('dashboard') ?>';
            }, 500);
        }, 1000); // Show loading for 2 seconds
    </script>
</body>
</html>