<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportTicketModel extends Model
{
    protected $table = 'support_tickets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id', 'user_id', 'user_email', 'user_name', 'user_department',
        'subject', 'description', 'category', 'priority', 'status',
        'attachment_url', 'attachment_filename', 'assigned_to', 'assigned_by',
        'resolved_at', 'resolved_by', 'last_response_at', 'response_count'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_email' => 'required|valid_email',
        'user_name' => 'permit_empty|string|max_length[255]',
        'subject' => 'required|string|max_length[500]',
        'description' => 'required|string',
        'category' => 'required|in_list[technical_issue,account_problem,matching_system,item_report_issue,notification_problem,other]',
        'priority' => 'permit_empty|in_list[low,medium,high,urgent]',
        'status' => 'permit_empty|in_list[open,in_progress,pending_user,resolved,closed]'
    ];

    protected $validationMessages = [
        'subject' => [
            'required' => 'Subject is required',
            'max_length' => 'Subject cannot exceed 500 characters'
        ],
        'description' => [
            'required' => 'Description is required'
        ],
        'user_email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all support tickets with optional filters
     */
    public function getTickets($status = null, $priority = null, $limit = null)
    {
        $builder = $this->orderBy('created_at', 'DESC');

        if ($status) {
            $builder->where('status', $status);
        }

        if ($priority) {
            $builder->where('priority', $priority);
        }

        if ($limit) {
            return $builder->findAll($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get ticket by ID (since we use ID as the ticket identifier)
     */
    public function getByTicketId($ticketId)
    {
        return $this->find($ticketId);
    }

    /**
     * Create a new support ticket
     */
    public function createTicket($data)
    {
        // Set defaults
        $data['status'] = $data['status'] ?? 'open';
        $data['priority'] = $data['priority'] ?? 'medium';
        
        // Generate unique ID if not provided
        if (!isset($data['id'])) {
            $data['id'] = 'TKT-' . date('Ymd') . '-' . strtoupper(uniqid());
        }

        return $this->insert($data);
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus($ticketId, $status)
    {
        $updateData = ['status' => $status];
        
        if ($status === 'resolved' || $status === 'closed') {
            $updateData['resolved_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($ticketId, $updateData);
    }

    /**
     * Get tickets count by status
     */
    public function getCountByStatus($status)
    {
        return $this->where('status', $status)
                   ->countAllResults(false);
    }

    /**
     * Get tickets statistics
     */
    public function getTicketsStatistics()
    {
        return [
            'total' => $this->countAllResults(false),
            'open' => $this->where('status', 'open')->countAllResults(false),
            'in_progress' => $this->where('status', 'in_progress')->countAllResults(false),
            'pending_user' => $this->where('status', 'pending_user')->countAllResults(false),
            'resolved' => $this->where('status', 'resolved')->countAllResults(false),
            'closed' => $this->where('status', 'closed')->countAllResults(false)
        ];
    }

    /**
     * Get ticket statistics (singular method name for controller compatibility)
     */
    public function getTicketStatistics()
    {
        return $this->getTicketsStatistics();
    }



    /**
     * Search tickets
     */
    public function searchTickets($searchTerm, $filters = [])
    {
        $builder = $this->groupStart()
                        ->like('subject', $searchTerm)
                        ->orLike('description', $searchTerm)
                        ->orLike('id', $searchTerm)
                        ->orLike('user_name', $searchTerm)
                        ->orLike('user_email', $searchTerm)
                        ->groupEnd();

        if (isset($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $builder->where('priority', $filters['priority']);
        }

        if (isset($filters['category'])) {
            $builder->where('category', $filters['category']);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get recent tickets for dashboard
     */
    public function getRecentTickets($limit = 10)
    {
        $builder = $this->db->table($this->table);
        $result = $builder->orderBy('created_at', 'DESC')
                         ->limit($limit)
                         ->get()
                         ->getResultArray();
        
        // Debug logging
        log_message('debug', 'getRecentTickets SQL: ' . $this->db->getLastQuery());
        log_message('debug', 'getRecentTickets result count: ' . count($result));
        
        return $result;
    }

    /**
     * Update last response timestamp
     */
    public function updateLastResponse($ticketId)
    {
        return $this->update($ticketId, [
            'last_response_at' => date('Y-m-d H:i:s'),
            'response_count' => $this->db->table($this->table)
                                       ->select('response_count')
                                       ->where('id', $ticketId)
                                       ->get()
                                       ->getRow()
                                       ->response_count + 1
        ]);
    }
}