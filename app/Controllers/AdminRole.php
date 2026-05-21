<?php

namespace App\Controllers;

use App\Models\AdminRoleModel;
use App\Models\EmailLogModel;

class AdminRole extends BaseController
{
    public function index()
    {
        $model = new AdminRoleModel();

        // Automatically add/update logged-in user to roles
        $session_email = session()->get('email');
        if ($session_email) {
            $user_role = $model->where('email', $session_email)->first();
            $session_pic = session()->get('profile_pic');

            if (!$user_role) {
                // User does not exist, create them
                $model->save([
                    'name'        => session()->get('name'),
                    'email'       => $session_email,
                    'role'        => 'Admin', // Default role
                    'profile_pic' => $session_pic
                ]);
            } elseif ($user_role['profile_pic'] !== $session_pic) {
                // User exists, but profile picture has changed, update it
                $model->update($user_role['id'], ['profile_pic' => $session_pic]);
            }
        }

        $q = $this->request->getGet('q');
        $role_filter = $this->request->getGet('role_filter');
        $data['q'] = $q;
        $data['role_filter'] = $role_filter;

        // Apply search filter
        if ($q) {
            $model->groupStart()
                  ->like('name', $q)
                  ->orLike('email', $q)
                  ->orLike('role', $q)
                  ->groupEnd();
        }

        // Apply role filter
        if ($role_filter) {
            $model->where('role', $role_filter);
        }

        // Get current user's role to determine what accounts they can see
        $currentUserRole = null;
        if ($session_email) {
            $currentUser = $model->where('email', $session_email)->first();
            $currentUserRole = $currentUser ? strtolower($currentUser['role']) : null;
        }

        // Hide Super_admin accounts from Admin and Staff users
        if ($currentUserRole && $currentUserRole !== 'super_admin') {
            $model->where('role !=', 'Super_admin');
        }

        // Use configurable per-page limit (default: 50, but allows up to 100)
        $perPage = (int) $this->request->getGet('per_page') ?: 50;
        $perPage = min($perPage, 100); // Cap at 100 for performance

        $data['roles'] = $model->paginate($perPage, 'roles');
        $data['pager'] = $model->pager;
        $data['per_page'] = $perPage;
        $data['currentUserRole'] = $currentUserRole; // Pass current user role to view
        return view('admin_role/index', $data);
    }



    public function edit($id)
    {
        $model = new AdminRoleModel();
        
        // Get the role being edited
        $roleToEdit = $model->find($id);
        if (!$roleToEdit) {
            return redirect()->to('/admin_role')->with('error', 'Role not found.');
        }
        
        // Get current user's session info
        $sessionEmail = session()->get('email');
        $currentUserRole = $model->where('email', $sessionEmail)->first();
        
        // Security check: Prevent users from accessing edit form for other users' accounts
        // Allow only if:
        // 1. User is editing their own account, OR
        // 2. User is an admin/super_admin editing any account
        // 3. Non-super_admin users cannot edit Super_admin accounts
        $isOwnAccount = (strtolower(trim($roleToEdit['email'])) === strtolower(trim($sessionEmail)));
        $isAdmin = $currentUserRole && in_array(strtolower($currentUserRole['role']), ['admin', 'super_admin']);
        $isSuperAdmin = $currentUserRole && strtolower($currentUserRole['role']) === 'super_admin';
        $targetIsSuperAdmin = strtolower($roleToEdit['role']) === 'super_admin';
        
        // Prevent non-super_admin users from editing Super_admin accounts
        if ($targetIsSuperAdmin && !$isSuperAdmin && !$isOwnAccount) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You cannot edit Super Admin accounts.');
        }
        
        if (!$isOwnAccount && !$isAdmin) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You can only edit your own account.');
        }
        
        $data['role'] = $roleToEdit;
        $data['isOwnAccount'] = $isOwnAccount;
        $data['isAdmin'] = $isAdmin;
        $data['currentUserRole'] = $currentUserRole ? strtolower($currentUserRole['role']) : null;
        
        return view('admin_role/edit', $data);
    }

    public function update($id)
    {
        $model = new AdminRoleModel();
        
        // Get the role being edited
        $roleToEdit = $model->find($id);
        if (!$roleToEdit) {
            return redirect()->to('/admin_role')->with('error', 'Role not found.');
        }
        
        // Get current user's session info
        $sessionEmail = session()->get('email');
        $currentUserRole = $model->where('email', $sessionEmail)->first();
        
        // Security check: Prevent users from editing other users' accounts
        // Allow only if:
        // 1. User is editing their own account, OR
        // 2. User is an admin/super_admin editing any account
        // 3. Non-super_admin users cannot edit Super_admin accounts
        $isOwnAccount = (strtolower(trim($roleToEdit['email'])) === strtolower(trim($sessionEmail)));
        $isAdmin = $currentUserRole && in_array(strtolower($currentUserRole['role']), ['admin', 'super_admin']);
        $isSuperAdmin = $currentUserRole && strtolower($currentUserRole['role']) === 'super_admin';
        $targetIsSuperAdmin = strtolower($roleToEdit['role']) === 'super_admin';
        
        // Prevent non-super_admin users from editing Super_admin accounts
        if ($targetIsSuperAdmin && !$isSuperAdmin && !$isOwnAccount) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You cannot edit Super Admin accounts.');
        }
        
        if (!$isOwnAccount && !$isAdmin) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You can only edit your own account.');
        }
        
        // Get the password from the form input
        $userPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        
        // Check requested role and enforce Super Admin restrictions
        $requestedRole = $this->request->getPost('role');
        // Lock role if editing a Super Admin account or if a Super Admin edits their own account
        if ($targetIsSuperAdmin || ($isOwnAccount && $isSuperAdmin)) {
            if ($requestedRole && strtolower($requestedRole) !== strtolower($roleToEdit['role'])) {
                return redirect()->back()->withInput()->with('error', 'Super Admin role cannot be changed.');
            }
        }
        // Only Super Admins can assign the Super Admin role to any account
        if (strtolower($requestedRole) === 'super_admin' && !$isSuperAdmin) {
            return redirect()->back()->withInput()->with('error', 'Access denied. Only Super Admins can assign Super Admin roles.');
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ];
        
        // Check if this email exists in the users table (Google accounts)
        // and get their profile image if available
        $db = \Config\Database::connect();
        $existingUser = $db->query("SELECT email, full_name, profile_image FROM users WHERE email = ?", [$data['email']])->getRowArray();
        
        if ($existingUser && !empty($existingUser['profile_image'])) {
            // User has a Google account with profile image
            $data['profile_pic'] = $existingUser['profile_image'];
        }
        
        // Additional security: Non-admin users cannot change their own role
        if ($isOwnAccount && !$isAdmin) {
            // Keep the original role for non-admin users editing themselves
            $data['role'] = $roleToEdit['role'];
        }
        // Enforce locked role for Super Admin targets or Super Admin editing self
        if ($targetIsSuperAdmin || ($isOwnAccount && $isSuperAdmin)) {
            $data['role'] = $roleToEdit['role'];
        }
        
        // Only update password if provided
        if (!empty($userPassword)) {
            // Additional security: Only allow password changes for own account or by admins
            if (!$isOwnAccount && !$isAdmin) {
                return redirect()->back()->withInput()->with('error', 'Access denied. You cannot change passwords for other accounts.');
            }
            
            // Validate password confirmation
            if ($userPassword !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
            }
            
            // Validate password strength
            $passwordError = $this->validatePasswordStrength($userPassword);
            if ($passwordError) {
                return redirect()->back()->withInput()->with('error', $passwordError);
            }
            
            // Add hashed password to data
            $data['password'] = password_hash($userPassword, PASSWORD_DEFAULT);
        }
        
        if ($model->update($id, $data)) {
            // Log role update activity
            $oldValues = [
                'name' => $roleToEdit['name'],
                'email' => $roleToEdit['email'],
                'role' => $roleToEdit['role']
            ];
            
            $newValues = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role']
            ];
            
            activity_logger()->logRoleUpdated(
                $id,
                $data['name'],
                $data['email'],
                session()->get('user_id'),
                $oldValues,
                $newValues
            );
            
            return redirect()->to('/admin_role')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update role.');
        }
    }

    public function delete($id)
    {
        $model = new AdminRoleModel();
        $role_to_delete = $model->find($id);
        $session_email = session()->get('email');

        if (!$role_to_delete) {
            return redirect()->to('/admin_role')->with('error', 'Role not found.');
        }

        if (strtolower(trim($role_to_delete['email'])) === strtolower(trim($session_email))) {
            return redirect()->to('/admin_role')->with('error', 'You cannot delete your own role.');
        }

        // Get current user's role to check permissions
        $currentUserRole = $model->where('email', $session_email)->first();
        $isSuperAdmin = $currentUserRole && strtolower($currentUserRole['role']) === 'super_admin';
        $targetIsSuperAdmin = strtolower($role_to_delete['role']) === 'super_admin';
        
        // Prevent non-super_admin users from deleting Super_admin accounts
        if ($targetIsSuperAdmin && !$isSuperAdmin) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You cannot delete Super Admin accounts.');
        }

        if ($model->delete($id)) {
            // Log role deletion activity
            activity_logger()->logRoleDeleted(
                $id,
                $role_to_delete['name'],
                $role_to_delete['email'],
                session()->get('user_id')
            );
            
            return redirect()->to('/admin_role')->with('success', 'Role deleted successfully.');
        } else {
            return redirect()->to('/admin_role')->with('error', 'Failed to delete role.');
        }
    }

    public function view($id)
    {
        $model = new AdminRoleModel();
        
        // Get the role being viewed
        $roleToView = $model->find($id);
        if (!$roleToView) {
            return redirect()->to('/admin_role')->with('error', 'Role not found.');
        }
        
        // Get current user's session info
        $sessionEmail = session()->get('email');
        $currentUserRole = $model->where('email', $sessionEmail)->first();
        
        // Security check: Prevent users from viewing other users' accounts
        // Allow only if:
        // 1. User is viewing their own account, OR
        // 2. User is an admin/super_admin viewing any account
        // 3. Non-super_admin users cannot view Super_admin accounts
        $isOwnAccount = (strtolower(trim($roleToView['email'])) === strtolower(trim($sessionEmail)));
        $isAdmin = $currentUserRole && in_array(strtolower($currentUserRole['role']), ['admin', 'super_admin']);
        $isSuperAdmin = $currentUserRole && strtolower($currentUserRole['role']) === 'super_admin';
        $targetIsSuperAdmin = strtolower($roleToView['role']) === 'super_admin';
        
        // Prevent non-super_admin users from viewing Super_admin accounts
        if ($targetIsSuperAdmin && !$isSuperAdmin && !$isOwnAccount) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You cannot view Super Admin accounts.');
        }
        
        if (!$isOwnAccount && !$isAdmin) {
            return redirect()->to('/admin_role')->with('error', 'Access denied. You can only view your own account.');
        }
        
        $data['role'] = $roleToView;
        return view('admin_role/view', $data);
    }

    public function create()
    {
        // Get current user's role to determine available options
        $sessionEmail = session()->get('email');
        $model = new AdminRoleModel();
        $currentUserRole = null;
        if ($sessionEmail) {
            $currentUser = $model->where('email', $sessionEmail)->first();
            $currentUserRole = $currentUser ? strtolower($currentUser['role']) : null;
        }
        
        $data['currentUserRole'] = $currentUserRole;
        return view('admin_role/create', $data);
    }

    public function store()
    {
        $model = new AdminRoleModel();
        $emailLogModel = new EmailLogModel();
        
        // Get current user's role to check permissions
        $sessionEmail = session()->get('email');
        $currentUserRole = $model->where('email', $sessionEmail)->first();
        $isSuperAdmin = $currentUserRole && strtolower($currentUserRole['role']) === 'super_admin';
        
        // Check if trying to create a Super_admin role
        $requestedRole = $this->request->getPost('role');
        if (strtolower($requestedRole) === 'super_admin' && !$isSuperAdmin) {
            return redirect()->back()->withInput()->with('error', 'Access denied. Only Super Admins can create Super Admin accounts.');
        }
        
        // Get the password from the form input
        $userPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        
        // Validate password confirmation
        if ($userPassword !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }
        
        // Validate password strength
        $passwordError = $this->validatePasswordStrength($userPassword);
        if ($passwordError) {
            return redirect()->back()->withInput()->with('error', $passwordError);
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'password' => password_hash($userPassword, PASSWORD_DEFAULT) // Hash for database
        ];
        
        // Check if this email exists in the users table (Google accounts)
        // and get their profile image if available
        $db = \Config\Database::connect();
        $existingUser = $db->query("SELECT email, full_name, profile_image FROM users WHERE email = ?", [$data['email']])->getRowArray();
        
        if ($existingUser && !empty($existingUser['profile_image'])) {
            // User has a Google account with profile image
            $data['profile_pic'] = $existingUser['profile_image'];
        }
        
        if ($model->save($data)) {
            // Get the inserted role ID
            $roleId = $model->getInsertID();
            
            // Log role creation activity
            activity_logger()->logRoleCreated(
                $roleId,
                $data['name'],
                $data['email'],
                $data['role'],
                session()->get('user_id')
            );
            
            // Prepare email data with plain text password
            $emailData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role'),
                'password' => $userPassword // Plain text for email
            ];
            
            // Send email notification to the new user with plain text password
            $this->sendRoleCreationNotification($emailData);
            
            // Send notification to all admin users
            $this->sendAdminNotification($emailData);
            
            return redirect()->to('/admin_role')->with('success', 'Role created successfully and notifications sent.');
        } else {
            return redirect()->back()->with('error', 'Failed to create role.');
        }
    }
    
    // Add this new method to generate random passwords
    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        $charactersLength = strlen($characters);
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $password;
    }
    
    private function sendRoleCreationNotification($roleData)
    {
        $emailLogModel = new EmailLogModel();
        
        // Use default template for role creation notification
        $subject = 'Welcome to CSPC Lost and Found - Your Account Details';
            $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Welcome to CSPC Lost and Found</title>
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                        background-color: #f5f5f5;
                        line-height: 1.6;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        padding: 40px 30px;
                        text-align: center;
                        color: white;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: 600;
                        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    }
                    .header p {
                        margin: 10px 0 0 0;
                        font-size: 16px;
                        opacity: 0.9;
                        font-weight: 300;
                    }
                    .content {
                        padding: 40px 30px;
                    }
                    .welcome-text {
                        font-size: 18px;
                        color: #333;
                        margin-bottom: 30px;
                        font-weight: 500;
                    }
                    .description {
                        color: #666;
                        margin-bottom: 30px;
                        font-size: 16px;
                    }
                    .credentials-section {
                        background-color: #f8f9fa;
                        border-radius: 8px;
                        padding: 25px;
                        margin: 30px 0;
                        border-left: 4px solid #667eea;
                    }
                    .credentials-title {
                        font-size: 16px;
                        font-weight: 600;
                        color: #333;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                    }
                    .credentials-title::before {
                        content: '🔐';
                        margin-right: 8px;
                        font-size: 18px;
                    }
                    .credential-item {
                        margin-bottom: 12px;
                        font-size: 15px;
                    }
                    .credential-label {
                        font-weight: 600;
                        color: #555;
                        display: inline-block;
                        width: 80px;
                    }
                    .credential-value {
                        color: #333;
                        font-family: 'Courier New', monospace;
                        background-color: #fff;
                        padding: 2px 6px;
                        border-radius: 4px;
                        border: 1px solid #e0e0e0;
                    }
                    .security-notice {
                        background-color: #fff3cd;
                        border: 1px solid #ffeaa7;
                        border-radius: 8px;
                        padding: 20px;
                        margin: 25px 0;
                    }
                    .security-notice h3 {
                        margin: 0 0 10px 0;
                        color: #856404;
                        font-size: 16px;
                        display: flex;
                        align-items: center;
                    }
                    .security-notice h3::before {
                        content: '⚠️';
                        margin-right: 8px;
                    }
                    .security-notice p {
                        margin: 0;
                        color: #856404;
                        font-size: 14px;
                    }
                    .action-button {
                        display: inline-block;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-decoration: none;
                        padding: 15px 30px;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 16px;
                        text-align: center;
                        margin: 20px 0;
                        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                        transition: all 0.3s ease;
                    }
                    .footer {
                        text-align: center;
                        padding: 30px;
                        color: #666;
                        font-size: 14px;
                        border-top: 1px solid #eee;
                    }
                    .footer p {
                        margin: 5px 0;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <h1>🏠 Welcome to CSPC Lost and Found</h1>
                        <p>Your gateway to excellence</p>
                    </div>
                    
                    <div class='content'>
                        <div class='welcome-text'>Welcome, {$roleData['name']}! 🎉</div>
                        
                        <div class='description'>
                            Your account has been created successfully. We're excited to have you join our community.
                        </div>
                        
                        <div class='credentials-section'>
                            <div class='credentials-title'>Your Account Credentials</div>
                            <div class='credential-item'>
                                <span class='credential-label'>Email:</span>
                                <span class='credential-value'>{$roleData['email']}</span>
                            </div>
                            <div class='credential-item'>
                                <span class='credential-label'>Password:</span>
                                <span class='credential-value'>{$roleData['password']}</span>
                            </div>
                            <div class='credential-item'>
                                <span class='credential-label'>Role:</span>
                                <span class='credential-value'>{$roleData['role']}</span>
                            </div>
                        </div>
                        
                        <div class='security-notice'>
                            <h3>Security Important</h3>
                            <p>For your security, please change your password immediately after your first login. Never share your credentials with anyone.</p>
                        </div>
                        
                        <div style='text-align: center;'>
                            <p>Ready to get started? Click below to access your account, or contact our support team if you need any assistance.</p>
                            <a href='https://lostfound.systems/' class='action-button'>Access My Account</a>
                        </div>
                    </div>
                    
                    <div class='footer'>
                        <p><strong>CSPC Lost and Found System</strong></p>
                        <p>Central Philippines State University</p>
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            // Replace placeholders with actual data
            $message = str_replace('{user_name}', $roleData['name'], $message);
            $message = str_replace('{user_email}', $roleData['email'], $message);
            $message = str_replace('{user_password}', $roleData['password'], $message);
            $message = str_replace('{user_role}', $roleData['role'], $message);
            $message = str_replace('{login_url}', 'https://lostfound.systems/', $message);
        
        // Log email attempt
        $logData = [
            'recipient_email' => $roleData['email'],
            'recipient_name' => $roleData['name'],
            'subject' => $subject,
            'body' => $message,
            'status' => 'pending',
            'sent_by' => 'System'
        ];
        
        $logId = $emailLogModel->insert($logData);
        
        // Send email
        $email = \Config\Services::email();
        
        try {
            $email->setTo($roleData['email'], $roleData['name']);
            $email->setSubject($subject);
            $email->setMessage($message);
            
            // Add timeout and debugging
            ini_set('max_execution_time', 60);
            
            if ($email->send()) {
                $emailLogModel->update($logId, [
                    'status' => 'sent',
                    'sent_at' => date('Y-m-d H:i:s')
                ]);
                log_message('info', 'Role creation notification sent successfully to: ' . $roleData['email']);
            } else {
                $errorMessage = $email->printDebugger(['headers', 'subject', 'body']);
                $emailLogModel->update($logId, [
                    'status' => 'failed',
                    'error_message' => 'SMTP Error: ' . $errorMessage
                ]);
                log_message('error', 'Failed to send role creation notification: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            $emailLogModel->update($logId, [
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            log_message('error', 'Email exception for role creation: ' . $e->getMessage());
            log_message('error', 'Email exception trace: ' . $e->getTraceAsString());
        }
    }
    
    private function sendAdminNotification($roleData)
    {
        $adminRoleModel = new AdminRoleModel();
        $emailLogModel = new EmailLogModel();
        
        // Get all admin users to notify (excluding super_admin)
        $adminUsers = $adminRoleModel->where('role', 'Admin')->findAll();
        
        $subject = 'New Role Created in CSPC Lost and Found System';
        $message = "
        <html>
        <body>
            <h2>New Role Created</h2>
            <p>A new role has been created in the CSPC Lost and Found System:</p>
            <ul>
                <li><strong>Name:</strong> {$roleData['name']}</li>
                <li><strong>Email:</strong> {$roleData['email']}</li>
                <li><strong>Role:</strong> {$roleData['role']}</li>
                <li><strong>Created by:</strong> " . (session()->get('name') ?? 'System') . "</li>
                <li><strong>Created at:</strong> " . date('Y-m-d H:i:s') . "</li>
            </ul>
            <p>Please review the new role assignment if necessary.</p>
            <br>
            <p>Best regards,<br>CSPC Lost and Found System</p>
        </body>
        </html>
        ";
        
        foreach ($adminUsers as $admin) {
            // Skip sending notification to the newly created user
            if ($admin['email'] === $roleData['email']) {
                continue;
            }
            
            // Log email attempt
            $logData = [
                'recipient_email' => $admin['email'],
                'recipient_name' => $admin['name'],
                'subject' => $subject,
                'body' => $message,
                'template_id' => null,
                'status' => 'pending',
                'sent_by' => 'System'
            ];
            
            $logId = $emailLogModel->insert($logData);
            
            // Send email
            $email = \Config\Services::email();
            
            try {
                $email->setTo($admin['email'], $admin['name']);
                $email->setSubject($subject);
                $email->setMessage($message);
                
                if ($email->send()) {
                    $emailLogModel->update($logId, [
                        'status' => 'sent',
                        'sent_at' => date('Y-m-d H:i:s')
                    ]);
                    log_message('info', 'Admin notification sent to: ' . $admin['email']);
                } else {
                    $errorMessage = $email->printDebugger(['headers']);
                    $emailLogModel->update($logId, [
                        'status' => 'failed',
                        'error_message' => 'SMTP Error: ' . $errorMessage
                    ]);
                    log_message('error', 'Failed to send admin notification: ' . $errorMessage);
                }
            } catch (\Exception $e) {
                $emailLogModel->update($logId, [
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
                log_message('error', 'Email exception for admin notification: ' . $e->getMessage());
            }
        }
    }
    
    /**
     * Validate password strength
     * 
     * @param string $password The password to validate
     * @return string|null Returns error message if validation fails, null if valid
     */
    private function validatePasswordStrength($password)
    {
        // Check minimum length
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters long.';
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return 'Password must contain at least one uppercase letter.';
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return 'Password must contain at least one lowercase letter.';
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one number.';
        }
        
        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return 'Password must contain at least one special character (!@#$%^&*(),.?":{}|<>).';
        }
        
        // Check for common weak passwords
        $weakPasswords = [
            '12345', '123456', '1234567', '12345678', '123456789', '1234567890',
            'password', 'password1', 'password123',
            'qwerty', 'qwerty123', 'qwertyuiop',
            'abc123', 'abc12345',
            'admin', 'admin123', 'admin1234',
            'letmein', 'welcome', 'monkey',
            '111111', '000000',
            'password!', 'Password1', 'Password123'
        ];
        
        $lowercasePassword = strtolower($password);
        foreach ($weakPasswords as $weakPassword) {
            if (strpos($lowercasePassword, strtolower($weakPassword)) !== false) {
                return 'Password is too weak. Please choose a stronger password.';
            }
        }
        
        // All validations passed
        return null;
    }
}
