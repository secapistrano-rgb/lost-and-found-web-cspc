<?php
// Test script to verify claims queries are working
require_once 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = new Config\Paths();
$bootstrap = rtrim(realpath(FCPATH . '../system/bootstrap.php') ?: FCPATH . '../system/boot.php', DIRECTORY_SEPARATOR);
require $bootstrap;

$app = Config\Services::codeigniter();
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

echo "Testing claims queries...\n\n";

// Test 1: Simple select with alias
echo "Test 1: Simple query with table alias 'c'\n";
try {
    $query = $db->table('claims c')
        ->select('c.id, c.item_id, c.match_id, c.claimant_id, c.claim_type, c.status, c.created_at, c.updated_at')
        ->limit(5);
    
    echo "Query SQL: " . $query->getCompiledSelect(false) . "\n";
    $results = $query->get()->getResultArray();
    echo "✓ Success! Found " . count($results) . " claims\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Query with joins
echo "Test 2: Query with joins and COALESCE\n";
try {
    $query = $db->table('claims c')
        ->select('c.id, c.claimant_id, c.status, c.created_at, i.title as item_name, u.full_name as claimant_name, COALESCE(u.email, c.claimant_id) as claimant_email')
        ->join('items i', 'i.id = c.item_id', 'left')
        ->join('users u', 'u.email = c.claimant_id', 'left')
        ->limit(5);
    
    echo "Query SQL: " . $query->getCompiledSelect(false) . "\n";
    $results = $query->get()->getResultArray();
    echo "✓ Success! Found " . count($results) . " claims with details\n\n";
    
    if (!empty($results)) {
        echo "Sample result:\n";
        print_r($results[0]);
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Count query
echo "Test 3: Count query\n";
try {
    $count = $db->table('claims c')
        ->where('c.status', 'pending')
        ->countAllResults();
    
    echo "✓ Success! Found " . $count . " pending claims\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

echo "All tests completed!\n";
