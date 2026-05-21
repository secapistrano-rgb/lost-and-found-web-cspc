<?php
// Script to clear session and force re-authentication

// Clear all session files
$sessionPath = __DIR__ . '/writable/session/';

echo "=== Clearing Session Files ===\n\n";

if (is_dir($sessionPath)) {
    $files = glob($sessionPath . 'ci_session*');
    
    if ($files) {
        echo "Found " . count($files) . " session file(s)\n\n";
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    echo "✓ Deleted: " . basename($file) . "\n";
                } else {
                    echo "✗ Failed to delete: " . basename($file) . "\n";
                }
            }
        }
        
        echo "\n✅ Session files cleared!\n";
        echo "\n📋 Next Steps:\n";
        echo "1. Close all browser windows\n";
        echo "2. Clear browser cache (Ctrl+Shift+Delete)\n";
        echo "3. Open browser again and login\n";
        echo "4. Admin should now see Feedback and Help & Support\n";
    } else {
        echo "No session files found.\n";
    }
} else {
    echo "Session directory not found: $sessionPath\n";
}
