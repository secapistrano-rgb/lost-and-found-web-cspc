<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportResponseModel extends Model
{
    protected $table = 'support_ticket_responses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id', 'ticket_id', 'responder_email', 'responder_name',
        'responder_type', 'message', 'attachment_url', 'attachment_filename',
        'is_internal'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $validationRules = [
        'ticket_id' => 'required|string',
        'responder_email' => 'required|valid_email',
        'message' => 'required|string',
        'responder_type' => 'permit_empty|in_list[support_staff,super_admin]'
    ];

    protected $validationMessages = [
        'content' => [
            'required' => 'Response content is required',
            'min_length' => 'Response must be at least 10 characters long'
        ],
        'ticket_id' => [
            'required' => 'Ticket ID is required'
        ],
        'user_name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 2 characters long'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all responses for a specific ticket
     */
    public function getTicketResponses($ticketId)
    {
        return $this->where('ticket_id', $ticketId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }

    /**
     * Create a new response to a ticket
     */
    public function createResponse($data)
    {
        // Generate unique ID if not provided
        if (!isset($data['id'])) {
            $data['id'] = 'RSP-' . date('Ymd') . '-' . strtoupper(uniqid());
        }
        
        // Set defaults
        $data['responder_type'] = $data['responder_type'] ?? 'support_staff';
        $data['is_internal'] = $data['is_internal'] ?? 0;

        return $this->insert($data);
    }

    /**
     * Get response count for a ticket
     */
    public function getResponseCount($ticketId)
    {
        return $this->where('ticket_id', $ticketId)
                   ->countAllResults();
    }

    /**
     * Get latest response for a ticket
     */
    public function getLatestResponse($ticketId)
    {
        return $this->where('ticket_id', $ticketId)
                   ->orderBy('created_at', 'DESC')
                   ->first();
    }

    /**
     * Get responses with ticket information
     */
    public function getResponsesWithTickets($limit = null)
    {
        $builder = $this->db->table('support_ticket_responses as r')
                           ->select('r.*, t.subject as ticket_subject, t.id as ticket_id')
                           ->join('support_tickets as t', 't.id = r.ticket_id')
                           ->orderBy('r.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Update response
     */
    public function updateResponse($responseId, $data)
    {
        return $this->update($responseId, $data);
    }

    /**
     * Delete response
     */
    public function deleteResponse($responseId)
    {
        return $this->delete($responseId);
    }

    /**
     * Check if response belongs to ticket
     */
    public function isResponseOfTicket($responseId, $ticketId)
    {
        $response = $this->where('id', $responseId)
                        ->where('ticket_id', $ticketId)
                        ->first();
        
        return !empty($response);
    }

    /**
     * Get responses by user email
     */
    public function getResponsesByUser($userEmail)
    {
        return $this->where('responder_email', $userEmail)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
}