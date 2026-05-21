<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class HelpSupportSeeder extends Seeder
{
    public function run()
    {
        // Insert sample tickets
        $tickets = [
            [
                'type' => 'ticket',
                'title' => 'Unable to login to my account',
                'content' => 'I am having trouble logging into my account. I keep getting an error message saying my credentials are invalid, but I am sure they are correct.',
                'category' => 'account',
                'priority' => 'high',
                'status' => 'open',
                'ticket_number' => 'TKT-' . date('Ymd') . '-0001',
                'user_name' => 'John Doe',
                'user_email' => 'john.doe@example.com',
                'created_by' => 'John Doe',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'type' => 'ticket',
                'title' => 'Feature request: Dark mode',
                'content' => 'It would be great if the application had a dark mode option for better user experience during night time usage.',
                'category' => 'general',
                'priority' => 'low',
                'status' => 'in_progress',
                'ticket_number' => 'TKT-' . date('Ymd') . '-0002',
                'user_name' => 'Jane Smith',
                'user_email' => 'jane.smith@example.com',
                'created_by' => 'Jane Smith',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'type' => 'ticket',
                'title' => 'Payment processing error',
                'content' => 'I tried to make a payment but received an error. The transaction was declined even though my card has sufficient funds.',
                'category' => 'billing',
                'priority' => 'urgent',
                'status' => 'resolved',
                'ticket_number' => 'TKT-' . date('Ymd') . '-0003',
                'user_name' => 'Mike Johnson',
                'user_email' => 'mike.johnson@example.com',
                'created_by' => 'Mike Johnson',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'resolved_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
        ];

        $this->db->table('help_support')->insertBatch($tickets);

        // Get the inserted ticket IDs
        $ticketIds = [];
        foreach ($tickets as $ticket) {
            $result = $this->db->table('help_support')
                              ->where('ticket_number', $ticket['ticket_number'])
                              ->get()
                              ->getRow();
            if ($result) {
                $ticketIds[] = $result->id;
            }
        }

        // Insert sample replies
        if (!empty($ticketIds)) {
            $replies = [
                [
                    'type' => 'reply',
                    'content' => 'Thank you for contacting us. We are looking into your login issue and will get back to you shortly.',
                    'parent_id' => $ticketIds[0],
                    'created_by' => 'Support Agent',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day 2 hours')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day 2 hours')),
                ],
                [
                    'type' => 'reply',
                    'content' => 'We have reset your password. Please check your email for the new temporary password.',
                    'parent_id' => $ticketIds[0],
                    'created_by' => 'Support Agent',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                ],
                [
                    'type' => 'reply',
                    'content' => 'Thank you for the feature request. We have added it to our development roadmap.',
                    'parent_id' => $ticketIds[1],
                    'created_by' => 'Product Manager',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                ],
                [
                    'type' => 'reply',
                    'content' => 'We have identified the issue with the payment processor and have resolved it. Please try again.',
                    'parent_id' => $ticketIds[2],
                    'created_by' => 'Technical Support',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                ],
            ];

            $this->db->table('help_support')->insertBatch($replies);
        }

        // Insert sample FAQs
        $faqs = [
            [
                'type' => 'faq',
                'title' => 'How do I reset my password?',
                'content' => 'To reset your password, click on the "Forgot Password" link on the login page and follow the instructions sent to your email.',
                'category' => 'account',
                'status' => 'active',
                'view_count' => 25,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 week')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 week')),
            ],
            [
                'type' => 'faq',
                'title' => 'How do I contact support?',
                'content' => 'You can contact support by creating a ticket through the Help & Support section or by emailing support@example.com.',
                'category' => 'general',
                'status' => 'active',
                'view_count' => 15,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 week')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 week')),
            ],
        ];

        $this->db->table('help_support')->insertBatch($faqs);
    }
}