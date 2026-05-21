<?php namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'description', 'category', 'type', 'status', 'location', 
        'latitude', 'longitude', 'date_lost_found', 'reporter_id', 'reporter_name', 
        'reporter_email', 'reporter_department', 'images', 'detected_objects', 'detected_categories'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'description' => 'permit_empty',
        'category' => 'permit_empty|max_length[100]',
        'type' => 'required|in_list[lost,found]',
        'status' => 'permit_empty|in_list[active,inactive,claimed,returned,resolved,Pending Turning-in,Fake Report]',
        'location' => 'permit_empty|max_length[255]',
        'latitude' => 'permit_empty|decimal',
        'longitude' => 'permit_empty|decimal',
        'date_lost_found' => 'permit_empty|valid_date',
        'reporter_id' => 'required|max_length[255]',
        'reporter_name' => 'permit_empty|max_length[255]',
        'reporter_email' => 'required|valid_email|max_length[255]',
        'reporter_department' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Item title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'type' => [
            'required' => 'Item type is required',
            'in_list' => 'Type must be either lost or found'
        ],
        'reporter_email' => [
            'required' => 'Reporter email is required',
            'valid_email' => 'Please provide a valid email address'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getLostItems()
    {
        return $this->where('type', 'lost')  // Changed from 'condition'
                   ->where('status', 'active')
                   ->orderBy('date_reported', 'DESC')
                   ->findAll();
    }
    
    public function getFoundItems()
    {
        return $this->where('type', 'found')  // Changed from 'condition'
                   ->where('status', 'active')
                   ->orderBy('date_reported', 'DESC')
                   ->findAll();
    }
    
    public function getItemsByReporter($reporterId)
    {
        return $this->where('reporter_id', $reporterId)
                   ->orderBy('date_reported', 'DESC')
                   ->findAll();
    }
    
    public function getItemsByCategory($category)
    {
        return $this->where('category', $category)
                   ->where('status', 'active')
                   ->orderBy('date_reported', 'DESC')
                   ->findAll();
    }
    
    public function searchItems($query, $type = null)  // Changed parameter name
    {
        $builder = $this->groupStart()
                       ->like('title', $query)
                       ->orLike('description', $query)
                       ->orLike('category', $query)
                       ->orLike('location', $query)
                       ->groupEnd();
        
        if ($type) {
            $builder->where('type', $type);  // Changed from 'condition'
        }
        
        return $builder->where('status', 'active')
                      ->orderBy('date_reported', 'DESC')
                      ->findAll();
    }
    
    public function getRecentItems($limit = 10)
    {
        return $this->where('status', 'active')
                   ->orderBy('date_reported', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
    
    public function getItemsWithImages()
    {
        return $this->where('images IS NOT NULL')
                   ->where('images !=', '')
                   ->where('status', 'active')
                   ->orderBy('date_reported', 'DESC')
                   ->findAll();
    }
    
    public function updateItemStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
    
    public function getItemStatistics()
    {
        $stats = [];
        
        $stats['total_items'] = $this->countAll();
        $stats['lost_items'] = $this->where('type', 'lost')->countAllResults();  // Changed from 'condition'
        $stats['found_items'] = $this->where('type', 'found')->countAllResults();  // Changed from 'condition'
        $stats['active_items'] = $this->where('status', 'active')->countAllResults();
        $stats['claimed_items'] = $this->where('status', 'claimed')->countAllResults();
        $stats['returned_items'] = $this->where('status', 'returned')->countAllResults();
        
        return $stats;
    }
}
