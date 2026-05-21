<?php
/**
 * Clear MySQL Query Cache and Table Metadata
 * Run this on VPS after deploying code changes
 */

require __DIR__ . '/vendor/autoload.php';

// Load CodeIgniter
$app = require_once FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require FCPATH . '../spark';

$db = \Config\Database::connect();

echo "Clearing MySQL caches...\n";

try {
    // Flush query cache
    $db->query('FLUSH QUERY CACHE');
    echo "✓ Query cache flushed\n";
} catch (\Exception $e) {
    echo "⚠ Query cache flush failed (may not be enabled): " . $e->getMessage() . "\n";
}

try {
    // Flush table cache
    $db->query('FLUSH TABLES');
    echo "✓ Table cache flushed\n";
} catch (\Exception $e) {
    echo "✗ Table cache flush failed: " . $e->getMessage() . "\n";
}

try {
    // Reset query cache
    $db->query('RESET QUERY CACHE');
    echo "✓ Query cache reset\n";
} catch (\Exception $e) {
    echo "⚠ Query cache reset failed (may not be enabled): " . $e->getMessage() . "\n";
}

echo "\nDone! Now restart PHP-FPM:\n";
echo "sudo systemctl restart php8.2-fpm\n";
echo "# or\n";
echo "sudo systemctl restart apache2\n";
