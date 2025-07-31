<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CSPC Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
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
            padding: 2.5rem 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .logo {
            width: 100px;
            height: 100px;
            margin: -70px auto 1rem;
            display: block;
            border: 5px solid #fff;
            border-radius: 50%;
            background-color: var(--cspc-primary);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .login-card h1 {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--cspc-primary);
            margin-bottom: 0.25rem;
        }
        .login-card .subtitle {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        .info-box {
            display: flex;
            align-items: center;
            background-color: #e9f5ff;
            border: 1px solid #b8dcfd;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #0c5460;
            text-align: left;
        }
        .info-box i {
            margin-right: 0.5rem;
        }
        .btn-google {
            background-color: #4285F4;
            color: white;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: background-color 0.3s;
        }
        .btn-google:hover {
            background-color: #357ae8;
            color: white;
        }
        .btn-google img {
            width: 20px;
            margin-right: 10px;
            background: #fff;
            border-radius: 50%;
            padding: 2px;
        }
        .help-link, .terms-link {
            display: block;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .terms-link {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="bg"></div>
    <div class="login-container">
        <div class="login-card">
            <img src="<?= base_url('images/logo.jpg') ?>" alt="CSPC Lost & Found Logo" class="logo">
            <h1>CSPC Smart Lost & Found App</h1>
            <p class="subtitle">Recover what's gone, restore what's here.</p>



            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                This app is exclusively for CSPC students and staff. Please sign in with your @my.cspc.edu.ph email account.
            </div>

            <a href="<?= $login_url ?>" class="btn btn-google d-flex align-items-center justify-content-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google icon">
                Sign in with Google
            </a>

            <a href="#" class="help-link">Need help signing in?</a>

            <p class="terms-link">By signing in, you agree to our Terms of Service and Privacy Policy</p>
        </div>
    </div>
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center" style="border-radius: 1rem; padding: 1.5rem;">
                <div class="modal-body">
                    <div class="error-icon mb-3">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="modal-title mb-2" id="errorModalLabel" style="color: #dc3545; font-weight: bold; font-size: 1.5rem;">Authentication Error</h5>
                    <p id="modal-error-message" class="text-muted"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            document.getElementById('modal-error-message').innerText = "<?= esc(session()->getFlashdata('error'), 'js') ?>";
            errorModal.show();
        });
    </script>
    <?php endif; ?>
</body>
</html>
