<?php

namespace App\Models;

use CodeIgniter\Model;

class HelpSupportModel extends Model
{
    protected $table = 'help_support';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'type', 'title', 'content', 'category', 'priority', 'status',
        'user_name', 'user_email', 'ticket_number', 'parent_id',
        'view_count', 'start_date', 'end_date', 'created_by', 'resolved_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'type' => 'required|in_list[faq,announcement,ticket,reply]',
        'content' => 'required|min_length[10]',
        'priority' => 'permit_empty|in_list[low,medium,high,urgent]',
        'status' => 'permit_empty|in_list[active,inactive,open,in_progress,resolved,closed]'
    ];

    protected $validationMessages = [
        'type' => [
            'required' => 'Type is required',
            'in_list' => 'Type must be one of: faq, announcement, ticket, reply'
        ],
        'content' => [
            'required' => 'Content is required',
            'min_length' => 'Content must be at least 10 characters long'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // ==================== FAQ METHODS ====================
    
    public function getFaqs($category = null, $activeOnly = true)
    {
        $builder = $this->where('type', 'faq');
        
        if ($activeOnly) {
            $builder->where('status', 'active');
        }
        
        if ($category) {
            $builder->where('category', $category);
        }
        
        return $builder->orderBy('category', 'ASC')
                      ->orderBy('title', 'ASC')
                      ->findAll();
    }

    public function getFaqCategories()
    {
        return $this->select('category')
                   ->where('type', 'faq')
                   ->where('status', 'active')
                   ->groupBy('category')
                   ->orderBy('category', 'ASC')
                   ->findAll();
    }

    public function incrementFaqViews($id)
    {
        $faq = $this->find($id);
        if ($faq && $faq['type'] === 'faq') {
            return $this->update($id, ['view_count' => $faq['view_count'] + 1]);
        }
        return false;
    }

    public function getMostViewedFaqs($limit = 10)
    {
        return $this->where('type', 'faq')
                   ->where('status', 'active')
                   ->orderBy('view_count', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    // ==================== ANNOUNCEMENT METHODS ====================
    
    public function getAnnouncements($type = null, $activeOnly = true)
    {
        $builder = $this->where('help_support.type', 'announcement');
        
        if ($activeOnly) {
            $builder->where('status', 'active')
                   ->where('(start_date IS NULL OR start_date <= CURDATE())')
                   ->where('(end_date IS NULL OR end_date >= CURDATE())');
        }
        
        if ($type) {
            $builder->where('category', $type);
        }
        
        return $builder->orderBy('priority', 'DESC')
                      ->orderBy('created_at', 'DESC')
                      ->findAll();
    }

    public function getActiveAnnouncements()
    {
        return $this->getAnnouncements(null, true);
    }

    // ==================== TICKET METHODS ====================
    
    public function getTickets($status = null, $category = null)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT t.*, 
                   (SELECT MAX(r.created_at) 
                    FROM help_support r 
                    WHERE r.type = 'reply' AND r.parent_id = t.id) as last_reply
            FROM help_support t
            WHERE t.type = 'ticket'
        ";
        
        $params = [];
        if ($status) {
            $sql .= " AND t.status = ?";
            $params[] = $status;
        }
        
        if ($category) {
            $sql .= " AND t.category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY t.created_at DESC";
        
        return $db->query($sql, $params)->getResultArray();
    }

    public function getTicketByNumber($ticketNumber)
    {
        return $this->where('type', 'ticket')
                   ->where('ticket_number', $ticketNumber)
                   ->first();
    }

    public function createTicket($data)
    {
        // Generate ticket number if not provided
        if (!isset($data['ticket_number'])) {
            $data['ticket_number'] = $this->generateTicketNumber();
        }
        
        $data['type'] = 'ticket';
        $data['status'] = $data['status'] ?? 'open';
        
        return $this->insert($data);
    }

    public function generateTicketNumber()
    {
        $prefix = 'TKT-' . date('Ymd') . '-';
        $lastTicket = $this->where('type', 'ticket')
                          ->where('ticket_number LIKE', $prefix . '%')
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket['ticket_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function updateTicketStatus($ticketId, $status)
    {
        $updateData = ['status' => $status];
        
        if (in_array($status, ['resolved', 'closed'])) {
            $updateData['resolved_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($ticketId, $updateData);
    }

    // ==================== REPLY METHODS ====================
    
    public function getTicketReplies($ticketId)
    {
        return $this->where('type', 'reply')
                   ->where('parent_id', $ticketId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }

    public function addReply($ticketId, $message, $createdBy, $isInternal = false)
    {
        $data = [
            'type' => 'reply',
            'content' => $message,
            'parent_id' => $ticketId,
            'created_by' => $createdBy,
            'status' => $isInternal ? 'inactive' : 'active' // Use status to mark internal replies
        ];
        
        return $this->insert($data);
    }

    // ==================== ANALYTICS METHODS ====================
    
    public function getTicketStatistics($startDate = null, $endDate = null)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT 
                COUNT(id) as total_tickets,
                SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open_tickets,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tickets,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_tickets,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_tickets
            FROM help_support 
            WHERE type = 'ticket'
        ";
        
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " AND DATE(created_at) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        return $db->query($sql, $params)->getRowArray();
    }

    public function getCommonCategories($type = 'ticket', $limit = 10)
    {
        return $this->select('category, COUNT(*) as count')
                   ->where('type', $type)
                   ->where('category IS NOT NULL')
                   ->groupBy('category')
                   ->orderBy('count', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function getDailyTrends($startDate, $endDate)
    {
        $db = \Config\Database::connect();
        
        return $db->query("
            SELECT 
                DATE(created_at) as date,
                SUM(CASE WHEN type = 'ticket' THEN 1 ELSE 0 END) as tickets_created,
                SUM(CASE WHEN type = 'ticket' AND status IN ('resolved', 'closed') THEN 1 ELSE 0 END) as tickets_resolved,
                SUM(CASE WHEN type = 'faq' THEN view_count ELSE 0 END) as faq_views
            FROM help_support 
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at)
            ORDER BY date
        ", [$startDate, $endDate])->getResultArray();
    }

    // ==================== SEARCH METHODS ====================
    
    public function searchContent($query, $type = null)
    {
        $builder = $this->groupStart()
                       ->like('title', $query)
                       ->orLike('content', $query)
                       ->orLike('category', $query)
                       ->groupEnd();
        
        if ($type) {
            $builder->where('type', $type);
        }
        
        return $builder->where('status !=', 'inactive')
                      ->orderBy('created_at', 'DESC')
                      ->findAll();
    }

    // ==================== UTILITY METHODS ====================
    
    public function getRecentActivity($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function getCountByType($type)
    {
        return $this->where('type', $type)
                   ->where('status !=', 'inactive')
                   ->countAllResults();
    }

    public function getCountByStatus($status, $type = null)
    {
        $builder = $this->where('status', $status);
        
        if ($type) {
            $builder->where('type', $type);
        }
        
        return $builder->countAllResults();
    }
}