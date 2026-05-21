<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailLogModel extends Model
{
    protected $table = 'email_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'recipient_email', 'recipient_name', 'subject', 'body',
        'template_id', 'status', 'sent_by', 'error_message', 'sent_at', 'attempts'
    ];
    protected $useTimestamps = true;

    // Get email logs with template information
    public function getEmailLogsWithTemplate($limit = 50)
    {
        return $this->select('email_logs.*, email_templates.name as template_name')
                   ->join('email_templates', 'email_templates.id = email_logs.template_id', 'left')
                   ->orderBy('email_logs.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
