<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailThreadModel extends Model
{
    protected $table = 'email_threads';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'message_id',
        'thread_id',
        'to_email',
        'from_email',
        'subject',
        'entity_type',
        'entity_id',
        'has_reply',
        'last_reply_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Track a sent email for reply detection
     */
    public function trackEmail($data)
    {
        return $this->insert([
            'message_id' => $data['message_id'] ?? '',
            'thread_id' => $data['thread_id'] ?? null,
            'to_email' => $data['to_email'],
            'from_email' => $data['from_email'] ?? 'noreply@lostfound.cspc.edu.ph',
            'subject' => $data['subject'],
            'entity_type' => $data['entity_type'] ?? null,
            'entity_id' => $data['entity_id'] ?? null,
        ]);
    }

    /**
     * Mark email as replied
     */
    public function markAsReplied($messageId)
    {
        $thread = $this->where('message_id', $messageId)->first();
        
        if ($thread) {
            return $this->update($thread['id'], [
                'has_reply' => 1,
                'last_reply_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        return false;
    }

    /**
     * Get threads by entity
     */
    public function getEntityThreads($entityType, $entityId)
    {
        return $this->where('entity_type', $entityType)
                    ->where('entity_id', $entityId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
