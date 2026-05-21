<?php

namespace App\Controllers;

use App\Models\AdminRoleModel;
use App\Helpers\ActivityLogger;
use CodeIgniter\Controller;

class Profile extends BaseController
{
    private $activityLogger;

    public function __construct()
    {
        parent::__construct();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    public function index()
    {
        // Log profile view activity
        $this->activityLogger->log(
            'Profile',
            'View Profile',
            'User viewed their profile page',
            [
                'severity' => 'low',
                'entity_type' => 'profile',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'user_id' => session()->get('user_id'),
                    'user_email' => session()->get('email'),
                    'user_name' => session()->get('name')
                ]
            ]
        );

        return view('profile/index');
    }

    public function changePassword()
    {
        $adminRoleModel = new AdminRoleModel();
        $userId = session()->get('user_id');
        $userEmail = session()->get('email');
        
        // Get current password and new passwords from form
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        
        // Validate inputs
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->activityLogger->log(
                'Profile',
                'Change Password Failed',
                'User attempted to change password but left required fields empty',
                [
                    'severity' => 'medium',
                    'entity_type' => 'profile',
                    'entity_id' => $userId,
                    'new_values' => [
                        'failure_reason' => 'Empty required fields'
                    ]
                ]
            );
            return redirect()->back()->with('error', 'All password fields are required.');
        }
        
        if ($newPassword !== $confirmPassword) {
            $this->activityLogger->log(
                'Profile',
                'Change Password Failed',
                'User attempted to change password but new passwords do not match',
                [
                    'severity' => 'medium',
                    'entity_type' => 'profile',
                    'entity_id' => $userId,
                    'new_values' => [
                        'failure_reason' => 'Password confirmation mismatch'
                    ]
                ]
            );
            return redirect()->back()->with('error', 'New passwords do not match.');
        }
        
        if (strlen($newPassword) < 8) {
            $this->activityLogger->log(
                'Profile',
                'Change Password Failed',
                'User attempted to change password but new password is too short',
                [
                    'severity' => 'medium',
                    'entity_type' => 'profile',
                    'entity_id' => $userId,
                    'new_values' => [
                        'failure_reason' => 'Password too short (less than 8 characters)',
                        'password_length' => strlen($newPassword)
                    ]
                ]
            );
            return redirect()->back()->with('error', 'New password must be at least 8 characters long.');
        }
        
        // Get current user from admin_role table
        $currentUser = $adminRoleModel->where('email', $userEmail)->first();
        
        if (!$currentUser) {
            return redirect()->back()->with('error', 'User not found.');
        }
        
        // Verify current password
        if (!password_verify($currentPassword, $currentUser['password'])) {
            // Log failed password change attempt
            $this->activityLogger->log(
                'Profile',
                'Change Password Failed',
                'User attempted to change password but provided incorrect current password',
                [
                    'severity' => 'medium',
                    'entity_type' => 'profile',
                    'entity_id' => $userId,
                    'new_values' => [
                        'failure_reason' => 'Incorrect current password'
                    ]
                ]
            );
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        
        // Update password
        $data = [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ];
        
        if ($adminRoleModel->update($currentUser['id'], $data)) {
            // Log successful password change
            $this->activityLogger->log(
                'Profile',
                'Change Password Success',
                'User successfully changed their password',
                [
                    'severity' => 'low',
                    'entity_type' => 'admin_role',
                    'entity_id' => $currentUser['id'],
                    'new_values' => [
                        'user_id' => $userId,
                        'user_email' => $userEmail,
                        'user_name' => $currentUser['name']
                    ]
                ]
            );
            return redirect()->back()->with('success', 'Password changed successfully.');
        } else {
            // Log failed password update
            $this->activityLogger->log(
                'Profile',
                'Change Password Failed',
                'User password change failed due to database update error',
                [
                    'severity' => 'high',
                    'entity_type' => 'admin_role',
                    'entity_id' => $currentUser['id'],
                    'new_values' => [
                        'user_id' => $userId,
                        'user_email' => $userEmail,
                        'user_name' => $currentUser['name'],
                        'failure_reason' => 'Database update failed'
                    ]
                ]
            );
            return redirect()->back()->with('error', 'Failed to update password.');
        }
    }
}