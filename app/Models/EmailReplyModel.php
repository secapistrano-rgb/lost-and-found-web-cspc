<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailReplyModel extends Model
{
    protected $table = 'email_replies';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'original_email_id', 'thread_id', 'from_email', 'from_name', 
        'subject', 'message_content', 'raw_email', 'is_read'
    ];
    protected $useTimestamps = true;

    // Get replies for a specific email thread
    public function getRepliesByThread($threadId)
    {
        return $this->where('thread_id', $threadId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }

    // Get replies for a specific original email
    public function getRepliesByOriginalEmail($originalEmailId)
    {
        return $this->where('original_email_id', $originalEmailId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }

    // Mark reply as read
    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => true]);
    }

    // Get unread replies count
    public function getUnreadCount()
    {
        return $this->where('is_read', false)->countAllResults();
    }

    // Get recent unread replies
    public function getRecentUnreadReplies($limit = 10)
    {
        return $this->where('is_read', false)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}