<?php

namespace App\Models;

use CodeIgniter\Model;

class ClaimModel extends Model
{
    protected $table = 'claims';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'item_id',
        'claimant_name',
        'claimant_contact',
        'claimant_email',
        'claim_description',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $validationRules = [
        'status' => 'required|in_list[pending,verified,rejected]'
    ];

    protected $validationMessages = [
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Please select a valid status'
        ]
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    
    protected function beforeInsert(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
    
    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}
