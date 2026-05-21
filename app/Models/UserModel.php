<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'google_id',
        'email',
        'password',
        'full_name',
        'profile_image',
        'department',
        'status',
        'suspended_until',
        'suspension_reason',
        'suspended_by',
        'email_verified',
        'two_factor_enabled',
        'last_login_at',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules for regular user management (password optional)
    protected $validationRules = [
        'email' => [
            'rules' => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
            'errors' => [
                'required' => 'Email is required',
                'valid_email' => 'Please provide a valid email address',
                'is_unique' => 'This email is already registered'
            ]
        ],
        'full_name' => 'required|max_length[255]',
        'department' => 'permit_empty|max_length[100]',
        'google_id' => 'required|max_length[255]|is_unique[users.google_id,id,{id}]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'required' => 'Email address is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email address is already registered'
        ],
        'google_id' => [
            'required' => 'Google ID is required',
            'is_unique' => 'This Google account is already registered'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    // Custom methods for API
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    public function findByGoogleId($googleId)
    {
        return $this->where('google_id', $googleId)->first();
    }
    
    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getAllUsers()
    {
        return $this->findAll();
    }
    
    // Hash password if provided (for future password functionality)
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    // Get user with department info
    public function getUserWithDepartment($id)
    {
        return $this->select('users.*, users.department as department_name')
                   ->where('users.id', $id)
                   ->first();
    }
    
    // Search users by multiple criteria
    public function searchUsers($query)
    {
        return $this->groupStart()
                   ->like('email', $query)
                   ->orLike('full_name', $query)
                   ->orLike('department', $query)
                   ->groupEnd()
                   ->findAll();
    }
    
    // Get all users excluding administration users
    public function getNonAdminUsers()
    {
        return $this->where('department !=', 'Administration')->findAll();
    }
    
    // Search non-admin users by multiple criteria
    public function searchNonAdminUsers($query)
    {
        return $this->where('department !=', 'Administration')
                   ->groupStart()
                   ->like('email', $query)
                   ->orLike('full_name', $query)
                   ->orLike('department', $query)
                   ->groupEnd()
                   ->findAll();
    }
    
    // Update user login timestamp
    public function updateLastLogin($id)
    {
        return $this->update($id, ['last_login_at' => date('Y-m-d H:i:s')]);
    }
    
    // Update user status
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
    
    // API-specific validation rules (password required)
    public function getApiValidationRules()
    {
        return [
            'email' => [
                'rules' => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please provide a valid email address',
                    'is_unique' => 'This email is already registered'
                ]
            ],
            'password' => 'permit_empty|min_length[6]',
            'full_name' => 'required|max_length[255]',
            'department' => 'permit_empty|max_length[100]',
            'google_id' => 'required|max_length[255]|is_unique[users.google_id,id,{id}]'
        ];
    }
}
