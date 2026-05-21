<?php
// Complete session and cache clear
session_start();
session_destroy();

// Clear all session files
$sessionPath = __DIR__ . '/writable/session';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/ci_session*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            echo "Deleted: $file\n";
        }
    }
}

echo "\nAll sessions cleared!\n";
echo "Please:\n";
echo "1. Clear your browser cache (Ctrl+Shift+Delete)\n";
echo "2. Close all browser tabs\n";
echo "3. Log in again\n";
?>
