<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_email' => 'test@my.cspc.edu.ph',
                'title' => 'Welcome to Lost & Found System',
                'message' => 'Welcome to the CSPC Lost & Found Management System. You can now report lost items and claim found items.',
                'type' => 'welcome',
                'priority' => 'medium',
                'is_read' => 0,
                'metadata' => json_encode(['source' => 'system', 'category' => 'welcome']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_email' => 'test@my.cspc.edu.ph',
                'title' => 'New Item Match Found',
                'message' => 'We found a potential match for your lost item "iPhone 13 Pro". Please check the matches section to review.',
                'type' => 'match',
                'priority' => 'high',
                'is_read' => 0,
                'metadata' => json_encode(['item_id' => 1, 'match_score' => 95, 'match_type' => 'lost_to_found']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'user_email' => 'test@my.cspc.edu.ph',
                'title' => 'Claim Request Approved',
                'message' => 'Your claim request for "Student ID Card" has been approved. Please visit the office to collect your item.',
                'type' => 'claim_approved',
                'priority' => 'high',
                'is_read' => 0,
                'metadata' => json_encode(['claim_id' => 1, 'item_name' => 'Student ID Card', 'office_location' => 'Admin Office']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'user_email' => 'test@my.cspc.edu.ph',
                'title' => 'System Maintenance Notice',
                'message' => 'The Lost & Found system will undergo maintenance on Sunday from 2:00 AM to 4:00 AM. Service may be temporarily unavailable.',
                'type' => 'system',
                'priority' => 'low',
                'is_read' => 1,
                'metadata' => json_encode(['maintenance_date' => '2025-01-26', 'duration' => '2 hours']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'user_email' => 'secapistrano@my.cspc.edu.ph',
                'title' => 'New Found Item Reported',
                'message' => 'A new found item "Laptop Charger" has been reported in your area. Check if it matches any of your lost items.',
                'type' => 'item_reported',
                'priority' => 'medium',
                'is_read' => 0,
                'metadata' => json_encode(['item_id' => 2, 'location' => 'Library', 'reporter' => 'John Doe']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
            ]
        ];

        // Check if notifications table exists and has data
        $builder = $this->db->table('notifications_web');
        $existingCount = $builder->countAllResults();
        
        if ($existingCount == 0) {
            $builder->insertBatch($data);
            echo "Created " . count($data) . " sample notifications\n";
        } else {
            echo "Notifications table already has data ($existingCount records). Skipping seeder.\n";
        }
    }
}