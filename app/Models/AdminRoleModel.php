<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminRoleModel extends Model
{
    protected $table = 'admin_role';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'role', 'profile_pic', 'password'];
    protected $useTimestamps = true;
}
