<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AdminRoleModel;
use App\Helpers\ActivityLogger;

class Login extends BaseController
{
    protected $userModel;
    protected $adminRoleModel;
    protected $activityLogger;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->adminRoleModel = new AdminRoleModel();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    public function index()
    {
        // Clear expired sessions for better security
        \App\Helpers\SessionHelper::clearExpiredSessions();
        
        // If already logged in, redirect to dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        // Set cache control headers to prevent caching of login page
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        // Log login page view
        $this->activityLogger->log(
            'Authentication',
            'View Login Page',
            'User accessed the login page',
            [
                'severity' => 'low'
            ]
        );

        return view('login/index');
    }

    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
        
        // Verify reCAPTCHA
        if (!$this->verifyRecaptcha($recaptchaResponse)) {
            // Log reCAPTCHA failure
            $this->activityLogger->log(
                'Authentication',
                'reCAPTCHA Failed',
                'Login attempt failed reCAPTCHA verification',
                [
                    'severity' => 'medium',
                    'entity_type' => 'security',
                    'new_values' => [
                        'attempted_email' => $email,
                        'failure_reason' => 'reCAPTCHA verification failed'
                    ]
                ]
            );
            
            return redirect()->to('/login')->with('error', 'Please complete the reCAPTCHA verification.');
        }

        // Validate input
        if (empty($email) || empty($password)) {
            return redirect()->to('/login')->with('error', 'Please enter both email and password.');
        }

        // Check if the email domain is from CSPC
        if (!str_ends_with($email, '@my.cspc.edu.ph')) {
            // Log invalid email domain attempt
            $this->activityLogger->log(
                'Authentication',
                'Invalid Email Domain',
                "Login attempt with invalid email domain: {$email}",
                [
                    'severity' => 'medium',
                    'entity_type' => 'security',
                    'new_values' => [
                        'attempted_email' => $email,
                        'failure_reason' => 'Invalid email domain (not @my.cspc.edu.ph)'
                    ]
                ]
            );
            
            return redirect()->to('/login')->with('error', 'Please use your @my.cspc.edu.ph email account.');
        }

        // First, try to authenticate against admin_role table
        $adminUser = $this->adminRoleModel->where('email', $email)->first();
        
        if ($adminUser && !empty($adminUser['password']) && password_verify($password, $adminUser['password'])) {
            // Admin/Staff user authentication successful
            $serverStartTime = $this->getServerStartTime();
            $sessionData = [
                'user_id' => $adminUser['id'],
                'name' => $adminUser['name'],
                'email' => $adminUser['email'],
                'department' => 'Administration', // Default for admin users
                'profile_pic' => !empty($adminUser['profile_pic']) ? $adminUser['profile_pic'] : base_url('images/logo.png'),
                'logged_in' => true,
                'user_role' => strtolower($adminUser['role']),
                'last_activity' => time(),
                'session_created' => time(),
                'server_start_time' => $serverStartTime,
                'user_data' => $adminUser
            ];
            session()->set($sessionData);
            
            // Log successful admin login
            $this->activityLogger->log(
                'Authentication',
                'Admin Login',
                "Admin user logged in: {$adminUser['name']} ({$adminUser['email']})",
                [
                    'severity' => 'medium',
                    'entity_type' => 'user',
                    'entity_id' => $adminUser['id'],
                    'new_values' => [
                        'user_name' => $adminUser['name'],
                        'user_email' => $adminUser['email'],
                        'user_role' => $adminUser['role'],
                        'login_type' => 'admin'
                    ]
                ]
            );
            
            return redirect()->to('/loading');
        }
        
        // If not found in admin_role, try regular users table
        $user = $this->userModel->verifyPassword($email, $password);
        
        if ($user) {
            // Regular user authentication successful
            // Update last login
            $this->userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            
            // Set session data
            $serverStartTime = $this->getServerStartTime();
            $sessionData = [
                'user_id' => $user['id'],
                'name' => $user['full_name'],
                'email' => $user['email'],
                'department' => $user['department'],
                'profile_pic' => !empty($user['profile_image']) ? $user['profile_image'] : base_url('images/logo.png'),
                'logged_in' => true,
                'user_role' => 'user', // Default role for regular users
                'last_activity' => time(),
                'session_created' => time(),
                'server_start_time' => $serverStartTime,
                'user_data' => $user
            ];
            session()->set($sessionData);
            
            // Log successful user login
            $this->activityLogger->log(
                'Authentication',
                'User Login',
                "User logged in: {$user['full_name']} ({$user['email']})",
                [
                    'severity' => 'medium',
                    'entity_type' => 'user',
                    'entity_id' => $user['id'],
                    'new_values' => [
                        'user_name' => $user['full_name'],
                        'user_email' => $user['email'],
                        'user_department' => $user['department'],
                        'login_type' => 'user'
                    ]
                ]
            );
            
            return redirect()->to('/loading');
        } else {
            // Log failed login attempt
        $this->activityLogger->log(
            'Authentication',
            'Failed Login',
            "Failed login attempt for email: {$email}",
            [
                'severity' => 'medium',
                'entity_type' => 'security',
                'new_values' => [
                    'attempted_email' => $email,
                    'failure_reason' => 'Invalid credentials'
                ]
            ]
        );
            
            return redirect()->to('/login')->with('error', 'Invalid email or password.');
        }
    }
    
    private function verifyRecaptcha($recaptchaResponse)
    {
        if (empty($recaptchaResponse)) {
            return false;
        }
        
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        if (empty($secretKey)) {
            log_message('error', 'reCAPTCHA secret key not configured');
            return true; // Allow login if not configured
        }
        
        $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
        
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $this->request->getIPAddress()
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($verifyURL, false, $context);
        
        if ($result === false) {
            log_message('error', 'reCAPTCHA verification failed: Unable to connect to Google servers');
            return true; // Allow login if reCAPTCHA service is down
        }
        
        $resultJson = json_decode($result, true);
        
        if (isset($resultJson['success']) && $resultJson['success'] === true) {
            return true;
        }
        
        // Log any error codes for debugging
        if (isset($resultJson['error-codes'])) {
            log_message('error', 'reCAPTCHA verification failed: ' . implode(', ', $resultJson['error-codes']));
        }
        
        return false;
    }

    public function logout()
    {
        // Log user logout before destroying session
        $userId = session()->get('user_id');
        $userEmail = session()->get('email');
        $userName = session()->get('name');
        $userRole = session()->get('user_role');
        
        if ($userId && $userEmail) {
            $this->activityLogger->log(
                'Authentication',
                'User Logout',
                "User logged out: {$userName} ({$userEmail})",
                [
                    'severity' => 'low',
                    'entity_type' => 'user',
                    'entity_id' => $userId,
                    'new_values' => [
                        'user_name' => $userName,
                        'user_email' => $userEmail,
                        'logout_time' => date('Y-m-d H:i:s')
                    ]
                ]
            );
        }
        
        // Completely destroy the session
        session()->destroy();
        
        // Set cache control headers to prevent back button access
        $response = $this->response;
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Cache-Control', 'post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        
        return redirect()->to('/login')->with('message', 'You have been logged out successfully.');
    }
    
    /**
     * Check if session is valid (AJAX endpoint)
     * This is called by JavaScript to prevent back button access
     */
    public function checkSession()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $this->response->setHeader('Pragma', 'no-cache');
        
        $isLoggedIn = session()->get('logged_in') ? true : false;
        
        return $this->response->setJSON([
            'logged_in' => $isLoggedIn,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Get server start time from marker file
     */
    private function getServerStartTime()
    {
        $markerFile = WRITEPATH . 'server_start_marker.txt';
        if (file_exists($markerFile)) {
            return file_get_contents($markerFile);
        }
        
        // Create marker file if it doesn't exist
        $startTime = time();
        file_put_contents($markerFile, $startTime);
        return $startTime;
    }
}
