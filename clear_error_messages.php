<?php
// Clear session error messages
session_start();

// Clear all flash data
if (isset($_SESSION['__ci_vars'])) {
    unset($_SESSION['__ci_vars']);
}

// Clear error message
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

// Clear any feedback-related session data
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'error') !== false || strpos($key, 'message') !== false) {
        unset($_SESSION[$key]);
    }
}

echo "Session error messages cleared!\n";
echo "Please refresh your browser page.\n";
?>
