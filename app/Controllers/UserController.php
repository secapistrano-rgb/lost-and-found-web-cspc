<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Libraries\EmailNotificationService;
use App\Helpers\ActivityLogger;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $notificationService;
    protected $activityLogger;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->notificationService = new EmailNotificationService();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    public function index()
    {
        $model = new UserModel();
        $q = $this->request->getGet('q');
        $department = $this->request->getGet('department');
        $status = $this->request->getGet('status');

        // Apply search filter
        if ($q) {
            $model->groupStart()
                  ->like('full_name', $q)
                  ->orLike('email', $q)
                  ->orLike('department', $q)
                  ->groupEnd();
        }
        
        // Apply department filter
        if ($department) {
            $model->where('department', $department);
        }
        
        // Apply status filter
        if ($status) {
            $model->where('status', $status);
        }
        
        // Use configurable per-page limit (default: 50, but allows up to 100)
        $perPage = (int) $this->request->getGet('per_page') ?: 50;
        $perPage = min($perPage, 100); // Cap at 100 for performance
        
        $users = $model->paginate($perPage, 'users');
        
        $pager = $model->pager;

        // Log activity for viewing users list
        $this->activityLogger->log(
            'Users',
            'View Users List',
            'User viewed the users management page',
            [
                'severity' => 'low',
                'entity_type' => 'user',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_users' => count($users),
                    'search_query' => $q,
                    'department_filter' => $department,
                    'status_filter' => $status
                ]
            ]
        );

        $data = [
            'users' => $users,
            'q'     => $q,
            'department' => $department,
            'status' => $status,
            'pager' => $pager,
            'per_page' => $perPage,
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $model = new UserModel();
        
        // Validate input data
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|max_length[255]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'department' => 'required|max_length[100]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', 'Please fix the validation errors: ' . implode(', ', $validation->getErrors()));
        }

        $data = [
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
            'department' => $this->request->getPost('department'),
            'status'     => 'active'
        ];

        if ($model->save($data)) {
            $userId = $model->getInsertID();
            
            // Log user creation activity
            $this->activityLogger->log(
                'Users',
                'Create User',
                "New user created: {$data['full_name']} ({$data['email']})",
                [
                    'severity' => 'medium',
                    'entity_type' => 'user',
                    'entity_id' => $userId,
                    'new_values' => [
                        'user_full_name' => $data['full_name'],
                        'user_email' => $data['email'],
                        'user_department' => $data['department'],
                        'user_status' => $data['status']
                    ]
                ]
            );
            

            
            return redirect()->to('/users')->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user: ' . implode(', ', $model->errors()));
        }
    }

    public function update($id)
    {
        $model = new UserModel();
        
        // Check if user exists
        $user = $model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }
        
        // Get the current email to check if it's being changed
        $currentEmail = $user['email'];
        $newEmail = $this->request->getPost('email');
        
        // Validate input data
        $validation = \Config\Services::validation();
        
        // Set validation rules - only check email uniqueness if email is being changed
        $emailRule = 'required|valid_email|max_length[150]';
        if ($currentEmail !== $newEmail) {
            $emailRule .= '|is_unique[users.email]';
        }
        
        $validation->setRules([
            'full_name' => 'required|max_length[255]',
            'email' => $emailRule,
            'department' => 'required|max_length[100]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . implode(', ', $validation->getErrors()));
        }

        $data = [
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $newEmail,
            'department' => $this->request->getPost('department')
        ];

        // Temporarily disable model validation to avoid conflicts
        $model->skipValidation(true);
        
        if ($model->update($id, $data)) {
            // Log user update activity
            $this->activityLogger->log(
                'Users',
                'Update User',
                "User updated: {$data['full_name']} ({$data['email']})",
                [
                    'severity' => 'medium',
                    'entity_type' => 'user',
                    'entity_id' => $id,
                    'old_values' => [
                        'full_name' => $user['full_name'],
                        'email' => $user['email'],
                        'department' => $user['department']
                    ],
                    'new_values' => [
                        'full_name' => $data['full_name'],
                        'email' => $data['email'],
                        'department' => $data['department']
                    ]
                ]
            );
            

            
            return redirect()->to('/users')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update user: Database error occurred.');
        }
    }

    public function edit($id)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        return view('users/edit', $data);
    }

    public function view($id)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the user with id: '. $id);
        }

        // Log activity for viewing user details
        $this->activityLogger->log(
            'Users',
            'View User Details',
            "User details viewed: {$data['user']['full_name']} ({$data['user']['email']})",
            [
                'severity' => 'low',
                'entity_type' => 'user',
                'entity_id' => $id,
                'new_values' => [
                    'user_full_name' => $data['user']['full_name'],
                    'user_email' => $data['user']['email'],
                    'user_department' => $data['user']['department'],
                    'user_status' => $data['user']['status']
                ]
            ]
        );

        return view('users/view', $data);
    }

    public function delete($id)
    {
        $model = new UserModel();
        
        // Get user data before deletion for logging
        $user = $model->find($id);
        
        if ($model->delete($id)) {
            // Log user deletion activity
            $this->activityLogger->log(
                'Users',
                'Delete User',
                "User deleted: {$user['full_name']} ({$user['email']})",
                [
                    'severity' => 'high',
                    'entity_type' => 'user',
                    'entity_id' => $id,
                    'old_values' => [
                        'user_full_name' => $user['full_name'],
                        'user_email' => $user['email'],
                        'user_department' => $user['department'],
                        'user_status' => $user['status']
                    ]
                ]
            );
            

        }

        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }

    public function suspend($id)
    {
        $model = new UserModel();
        
        // Check if user exists
        $user = $model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }
        
        // Get suspension data from POST
        $suspensionType = $this->request->getPost('suspension_type');
        $suspensionDays = $this->request->getPost('suspension_days');
        $suspensionReason = $this->request->getPost('suspension_reason');
        
        $suspendedUntil = null;
        if ($suspensionType === 'temporary' && $suspensionDays) {
            $suspendedUntil = date('Y-m-d H:i:s', strtotime("+{$suspensionDays} days"));
        }
        
        // Set the correct status based on suspension type
        $status = ($suspensionType === 'permanent') ? 'Permanently Suspended' : 'Temporary Suspended';
        
        $data = [
            'status' => $status,
            'suspended_until' => $suspendedUntil,
            'suspension_reason' => $suspensionReason,
            'suspended_by' => session()->get('email')
        ];
        
        if ($model->update($id, $data)) {
            // Log user suspension activity
            $this->activityLogger->log(
                'Users',
                'Suspend User',
                "User suspended: {$user['full_name']} ({$user['email']}) - {$suspensionType}",
                [
                    'severity' => 'medium',
                    'entity_type' => 'user',
                    'entity_id' => $id,
                    'old_values' => [
                        'status' => $user['status']
                    ],
                    'new_values' => [
                        'status' => $status,
                        'suspension_type' => $suspensionType,
                        'suspension_days' => $suspensionDays,
                        'suspension_reason' => $suspensionReason,
                        'suspended_until' => $suspendedUntil
                    ]
                ]
            );
            

            
            $suspensionText = $suspensionType === 'permanent' ? 'permanently' : "for {$suspensionDays} days";
            return redirect()->to('/users')->with('success', "User suspended {$suspensionText} successfully.");
        } else {
            return redirect()->back()->with('error', 'Failed to suspend user.');
        }
    }
    
    public function unsuspend($id)
    {
        $model = new UserModel();
        
        // Check if user exists
        $user = $model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }
        
        $data = [
            'status' => 'active',
            'suspended_until' => null,
            'suspension_reason' => null,
            'suspended_by' => null
        ];
        
        if ($model->update($id, $data)) {
            // Log user unsuspension activity
            $this->activityLogger->log(
                'Users',
                'Unsuspend User',
                "User unsuspended: {$user['full_name']} ({$user['email']})",
                [
                    'severity' => 'low',
                    'entity_type' => 'user',
                    'entity_id' => $id,
                    'old_values' => [
                        'status' => $user['status']
                    ],
                    'new_values' => [
                        'status' => 'active'
                    ]
                ]
            );
            

            
            return redirect()->to('/users')->with('success', 'User unsuspended successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to unsuspend user.');
        }
    }
}
