<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CSPC Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- reCAPTCHA v2 Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        :root {
            --cspc-primary: #003366;
            --cspc-secondary: #004d99;
        }
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        
        * {
            box-sizing: border-box;
        }
        .bg {
            background-image: url('<?= base_url('images/school-background.jpg') ?>');
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            filter: blur(4px);
            -webkit-filter: blur(4px);
            position: absolute;
            width: 100%;
            z-index: -1;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
            z-index: 1;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 400px;
            width: 90%;
            padding: 1.5rem 1.5rem 1.5rem;
            position: relative;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            animation: slideInUp 0.6s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: -40px auto 1rem;
            display: block;
            border: 3px solid #fff;
            border-radius: 50%;
            background-color: var(--cspc-primary);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            object-fit: cover;
            object-position: center;
            position: relative;
            z-index: 10;
        }
        .login-card h1 {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--cspc-primary);
            margin-bottom: 0.5rem;
            line-height: 1.3;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .login-card .subtitle {
            color: #6b7280;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            font-weight: 400;
        }
        .info-box {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #e1f5fe 0%, #f3e5f5 100%);
            border: 1px solid #81d4fa;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            color: #01579b;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .info-box i {
            margin-right: 0.5rem;
        }
        .form-control {
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #ffffff;
            width: 100%;
            text-align: left;
            font-weight: 400;
        }
        .form-control:focus {
            border-color: var(--cspc-primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.1);
            background-color: #ffffff;
            outline: none;
        }
        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        .form-label {
            font-weight: 600;
            color: var(--cspc-primary);
            margin-bottom: 0.5rem;
            display: block;
            text-align: left;
            font-size: 0.9rem;
            letter-spacing: 0.025em;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--cspc-primary) 0%, var(--cspc-secondary) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--cspc-secondary) 0%, #0066cc 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.4);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .alert {
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            font-weight: 500;
        }
        .help-link, .terms-link {
            display: block;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .terms-link {
            color: #9ca3af;
            font-size: 0.75rem;
            margin-top: 1rem;
            font-weight: 400;
        }
        .password-input-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            font-size: 1.1rem;
            z-index: 10;
        }
        .password-toggle:hover {
            color: var(--cspc-primary);
        }
        .password-input-container .form-control {
            padding-right: 40px;
        }
        .recaptcha-container {
            margin: 0.75rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 78px;
        }
        .recaptcha-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .recaptcha-loading .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
            margin-right: 0.5rem;
        }
        .recaptcha-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }
        
        /* Mobile Responsive Design */
        @media (max-width: 768px) {
            .login-card {
                max-width: 95%;
                padding: 2rem 1.5rem;
                margin: 1rem;
                min-height: auto;
            }
            
            .logo {
                width: 100px;
                height: 100px;
                margin: -50px auto 1rem;
            }
            
            .login-card h1 {
                font-size: 1.25rem;
            }
            
            .form-control {
                padding: 0.875rem 1rem;
                font-size: 0.95rem;
            }
            
            .btn-primary {
                padding: 0.875rem 1.5rem;
                font-size: 0.95rem;
            }
            
            .info-box {
                font-size: 0.8rem;
                padding: 0.625rem 0.875rem;
            }
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem 1rem;
                border-radius: 1rem;
            }
            
            .logo {
                width: 80px;
                height: 80px;
                margin: -40px auto 1rem;
            }
            
            .login-card h1 {
                font-size: 1.1rem;
                line-height: 1.2;
            }
        }
    </style>
</head>
<body>
    <div class="bg"></div>
    <div class="login-container">
        <div class="login-card">
            <img src="<?= base_url('images/logo.png') ?>" alt="CSPC Lost & Found Logo" class="logo">
            
            <h1>CSPC Admin & Support Staff Login</h1>




            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                This panel is exclusively for CSPC Admin and Support Staff. Please sign in with your @my.cspc.edu.ph email account.
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login/authenticate') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="your.email@my.cspc.edu.ph" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-container">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="password-icon"></i>
                        </button>
                    </div>
                </div>
                
                <div id="recaptcha-error" class="recaptcha-error" style="display: none;"></div>
                
                <!-- reCAPTCHA v2 Widget -->
                <div class="g-recaptcha mb-3" data-sitekey="<?= env('RECAPTCHA_SITE_KEY') ?>"></div>
                
                <button type="submit" class="btn btn-primary w-100 mb-2" id="login-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sign In
                </button>
            </form>



            <p class="terms-link">By signing in, you agree to our Terms of Service and Privacy Policy</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'bi bi-eye';
            }
        }

        function showRecaptchaError(message) {
            const errorDiv = document.getElementById('recaptcha-error');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        function hideRecaptchaError() {
            const errorDiv = document.getElementById('recaptcha-error');
            errorDiv.style.display = 'none';
        }

        // Form submission validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                hideRecaptchaError();

                // Check if reCAPTCHA is completed
                const recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length === 0) {
                    showRecaptchaError('Please complete the reCAPTCHA verification.');
                    return false;
                }

                // Submit the form if reCAPTCHA is completed
                form.submit();
            });
        });

        // Prevent back button access to login page after successful login
        window.addEventListener('pageshow', function(event) {
            // Check if the page is loaded from cache (back button)
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Force reload the page to re-check authentication
                window.location.reload();
            }
        });

        // Disable browser back button caching
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
