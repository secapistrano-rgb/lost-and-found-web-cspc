<?php namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'description',
        'category',
        'status',
        'condition',
        'image',
        'found_date',
        'email',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
