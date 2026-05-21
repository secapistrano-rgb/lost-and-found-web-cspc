<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaginationTestSeeder extends Seeder
{
    public function run()
    {
        // Use the current logged-in user's email or default to test email
        $userEmail = 'chfrondozo@my.cspc.edu.ph'; // Default test email
        
        $data = [
            [
                'user_email' => $userEmail,
                'title' => 'New Lost Item Alert',
                'message' => 'A new lost item "Wireless Earbuds" has been reported. Check if you have found this item.',
                'type' => 'item',
                'priority' => 'medium',
                'is_read' => 0,
                'metadata' => json_encode(['item_id' => 101, 'category' => 'electronics', 'location' => 'Cafeteria']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ],
            [
                'user_email' => $userEmail,
                'title' => 'Feedback Request',
                'message' => 'Please provide feedback on your recent experience with the Lost & Found system. Your input helps us improve our services.',
                'type' => 'feedback',
                'priority' => 'low',
                'is_read' => 0,
                'metadata' => json_encode(['feedback_type' => 'service_rating', 'survey_id' => 'SF2025001']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
            [
                'user_email' => $userEmail,
                'title' => 'Urgent: Claim Deadline Approaching',
                'message' => 'Your claim for "Mathematics Textbook" expires in 2 days. Please collect your item from the Admin Office before the deadline.',
                'type' => 'claim',
                'priority' => 'urgent',
                'is_read' => 0,
                'metadata' => json_encode(['claim_id' => 205, 'deadline' => date('Y-m-d', strtotime('+2 days')), 'office' => 'Admin Office']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'user_email' => $userEmail,
                'title' => 'Security Alert',
                'message' => 'Multiple login attempts detected from an unknown device. If this was not you, please change your password immediately.',
                'type' => 'security',
                'priority' => 'high',
                'is_read' => 0,
                'metadata' => json_encode(['ip_address' => '192.168.1.100', 'device' => 'Unknown Device', 'attempts' => 3]),
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'user_email' => $userEmail,
                'title' => 'Weekly Report Available',
                'message' => 'Your weekly Lost & Found activity report is now available. View your statistics and recent activities.',
                'type' => 'report',
                'priority' => 'low',
                'is_read' => 0,
                'metadata' => json_encode(['report_period' => 'Week 4, January 2025', 'items_reported' => 2, 'matches_found' => 1]),
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
            ]
        ];

        // Insert the test notifications
        $builder = $this->db->table('notifications_web');
        $builder->insertBatch($data);
        
        echo "Created " . count($data) . " additional test notifications for pagination testing\n";
        echo "User email: " . $userEmail . "\n";
    }
}