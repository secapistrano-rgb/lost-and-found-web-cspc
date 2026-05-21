<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use App\Models\UserModel;

class TestUserData extends BaseCommand
{
    protected $group       = 'test';
    protected $name        = 'test:userdata';
    protected $description = 'Test user data retrieval';

    public function run(array $params)
    {
        $model = new UserModel();
        $users = $model->findAll();

        echo "Testing user data retrieval:\n";
        echo "Total users found: " . count($users) . "\n\n";

        if (!empty($users)) {
            $firstUser = $users[0];
            echo "First user data:\n";
            echo "ID: " . ($firstUser['id'] ?? 'Not set') . "\n";
            echo "Email: " . ($firstUser['email'] ?? 'Not set') . "\n";
            echo "Full Name: " . ($firstUser['full_name'] ?? 'Not set') . "\n";
            echo "Profile Image: " . ($firstUser['profile_image'] ?? 'Not set') . "\n";
            echo "Department: " . ($firstUser['department'] ?? 'Not set') . "\n";
            
            echo "\nAll available fields:\n";
            foreach (array_keys($firstUser) as $field) {
                echo "- " . $field . "\n";
            }
        }
    }
}