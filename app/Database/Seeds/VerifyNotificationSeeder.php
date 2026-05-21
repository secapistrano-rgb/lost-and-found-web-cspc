<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VerifyNotificationSeeder extends Seeder
{
    public function run()
    {
        $userEmail = 'chfrondozo@my.cspc.edu.ph';
        
        echo "Verifying notifications for user: $userEmail\n";
        echo "========================================\n\n";
        
        // Get notifications for the user
        $builder = $this->db->table('notifications_web');
        $notifications = $builder->where('user_email', $userEmail)
                                ->orderBy('created_at', 'DESC')
                                ->get()
                                ->getResultArray();
        
        if (empty($notifications)) {
            echo "❌ No notifications found for user: $userEmail\n";
            return;
        }
        
        echo "✅ Found " . count($notifications) . " notifications for user: $userEmail\n\n";
        
        foreach ($notifications as $index => $notification) {
            echo "Notification " . ($index + 1) . ":\n";
            echo "  ID: " . $notification['id'] . "\n";
            echo "  Title: " . $notification['title'] . "\n";
            echo "  Type: " . $notification['type'] . "\n";
            echo "  Priority: " . $notification['priority'] . "\n";
            echo "  Is Read: " . ($notification['is_read'] ? 'Yes' : 'No') . "\n";
            echo "  Created: " . $notification['created_at'] . "\n";
            echo "  Message: " . substr($notification['message'], 0, 80) . "...\n";
            echo "\n";
        }
        
        echo "========================================\n";
        echo "Verification completed successfully!\n";
        echo "All notifications are properly stored in the notifications_web table.\n";
    }
}