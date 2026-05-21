<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\AdminRoleModel;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in first
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Get user role from database, fallback to session
        $adminRoleModel = new AdminRoleModel();
        $session_email = session()->get('email');
        $user_role = $session_email ? $adminRoleModel->where('email', $session_email)->first() : null;
        $sessionRole = session()->get('user_role');
        
        if ($user_role) {
            $originalRole = trim($user_role['role']);
        } elseif (!empty($sessionRole)) {
            $originalRole = trim($sessionRole);
        } else {
            return redirect()->to('/login')->with('error', 'User role not found.');
        }

        // Store original role and create normalized version for permission checking
        // $originalRole already set above (DB or session)
    $normalizedRole = strtolower(str_replace(' ', '_', trim($originalRole)));
        $uri = $request->getUri()->getPath();
        
        // Define role permissions (using normalized role names)
        $permissions = [
            'super_admin' => ['*'], // Full access
            'admin' => [
                'dashboard', 'items', 'claims', 'emails', 'users', 'admin_role', 
                'reports', 'feedback', 'help-support', 'settings', 'notifications',
                'analytics', 'export', 'import', 'logs'
            ], // Explicit admin permissions
            'staff' => [
                'dashboard', 'claims', 'feedback', 'help-support', 'items', 'notifications'
            ]
        ];
        
        // Debug logging
        log_message('debug', "RoleFilter - Email: $session_email, Original Role: $originalRole, Normalized Role: $normalizedRole, URI: $uri");
        
        // Get first segment for permission checking
        $firstSegment = trim($uri, '/');
        $segments = explode('/', $firstSegment);
        $checkSegment = $segments[0] ?? '';
        
        // Additional debug for feedback and help-support
        if (in_array($checkSegment, ['feedback', 'help-support', 'notifications'])) {
            log_message('debug', "RoleFilter - Checking access to: $checkSegment");
            log_message('debug', "RoleFilter - User role: $normalizedRole");
            log_message('debug', "RoleFilter - User role permissions: " . json_encode($permissions[$normalizedRole] ?? []));
        }
        
        // Hard allow: Admins and Staff and Super_Admin have their explicit permissions
        if (in_array($normalizedRole, ['admin', 'super_admin', 'staff'])) {
            log_message('debug', "RoleFilter - Auto allow for $normalizedRole to access '$uri'");
            session()->set('user_role', $normalizedRole);
            session()->set('user_role_original', $originalRole);
            
            // For staff, check if they have permission
            if ($normalizedRole === 'staff') {
                if (!$this->hasPermission($normalizedRole, $uri, $permissions)) {
                    log_message('warning', "RoleFilter - Access DENIED for staff to URI '$uri' (segment: $checkSegment)");
                    return redirect()->to('/dashboard')->with('error', 'Access denied. You do not have permission to access this resource.');
                }
            }
            
            return; // allow request to proceed
        }

        // Explicit allow for admin roles to feedback and help-support
        if (in_array($normalizedRole, ['admin', 'super_admin']) && in_array($checkSegment, ['feedback', 'help-support'])) {
            log_message('debug', "RoleFilter - Explicit allow for $normalizedRole to access '$checkSegment'");
            session()->set('user_role', $normalizedRole);
            session()->set('user_role_original', $originalRole);
            return; // allow request to proceed
        }
        
        // Check if user has permission for current route
        if (!$this->hasPermission($normalizedRole, $uri, $permissions)) {
            log_message('warning', "RoleFilter - Access DENIED for role '$normalizedRole' to URI '$uri' (segment: $checkSegment)");
            return redirect()->to('/dashboard')->with('error', 'Access denied. You do not have permission to access this resource.');
        }
        
        log_message('debug', "RoleFilter - Access GRANTED for role '$normalizedRole' to URI '$uri'");
        
        // Store both original and normalized role in session for consistency
        session()->set('user_role', $normalizedRole); // For permission checking
        session()->set('user_role_original', $originalRole); // For display purposes
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Prevent browser caching of protected pages
        // This prevents users from using the back button to access pages after logout
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Cache-Control', 'post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        
        return $response;
    }
    
    private function hasPermission($userRole, $uri, $permissions)
    {
        // Remove leading slash and get first segment
        $uri = trim($uri, '/');
        $segments = explode('/', $uri);
        $firstSegment = $segments[0] ?? '';
        
        // Allow access to login and logout
        if (in_array($firstSegment, ['login', 'logout', ''])) {
            return true;
        }
        
        // Check if user role has permission for this route
        if (!isset($permissions[$userRole])) {
            return false;
        }
        
        // Check for wildcard (full access)
        if (in_array('*', $permissions[$userRole])) {
            return true;
        }
        
        return in_array($firstSegment, $permissions[$userRole]);
    }
}