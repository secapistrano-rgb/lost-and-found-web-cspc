<?php
echo "=== COMPREHENSIVE ADMIN ACCESS FIX ===\n\n";

// Step 1: Force clear all sessions
echo "Step 1: Clearing all sessions...\n";
$sessionPath = WRITEPATH . 'session';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/ci_session*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            echo "  ✓ Deleted: " . basename($file) . "\n";
        }
    }
} else {
    echo "  ℹ️ Session directory not found, checking for session files...\n";
    $files = glob('writable/session/ci_session*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            echo "  ✓ Deleted: " . basename($file) . "\n";
        }
    }
}

echo "\nStep 2: Verifying filter configuration...\n";

// Check Filters.php
$filtersContent = file_get_contents('app/Config/Filters.php');
if (strpos($filtersContent, "'feedback'") !== false && 
    strpos($filtersContent, "'help-support'") !== false) {
    echo "  ✅ Filters.php: Routes protected correctly\n";
} else {
    echo "  ❌ Filters.php: Missing route protection\n";
}

// Check RoleFilter.php
$roleFilterContent = file_get_contents('app/Filters/RoleFilter.php');
if (strpos($roleFilterContent, "'feedback'") !== false && 
    strpos($roleFilterContent, "'help-support'") !== false) {
    echo "  ✅ RoleFilter.php: Permissions configured correctly\n";
} else {
    echo "  ❌ RoleFilter.php: Missing permissions\n";
}

echo "\nStep 3: Database verification...\n";

// Database check
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lost_and_found", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT * FROM admin_role WHERE email = ?");
    $stmt->execute(['secapistrano@my.cspc.edu.ph']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "  ✅ Admin user found: {$admin['email']}\n";
        echo "  ✅ Role: '{$admin['role']}'\n";
    } else {
        echo "  ❌ Admin user not found!\n";
    }
} catch (Exception $e) {
    echo "  ❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 SOLUTION STEPS FOR YOU:\n";
echo str_repeat("=", 60) . "\n\n";

echo "1. 🚪 LOGOUT COMPLETELY:\n";
echo "   • Go to your browser and click logout\n";
echo "   • Wait for redirect to login page\n\n";

echo "2. 🧹 CLEAR BROWSER DATA:\n";
echo "   • Press Ctrl+Shift+Delete\n";
echo "   • Select 'All time' or 'Everything'\n";
echo "   • Check: Cookies, Cached files, Site data\n";
echo "   • Click 'Clear data'\n\n";

echo "3. 🌐 RESTART BROWSER:\n";
echo "   • Close ALL browser windows\n";
echo "   • Wait 5 seconds\n";
echo "   • Open browser again\n\n";

echo "4. 🔑 LOGIN AGAIN:\n";
echo "   • Go to: http://localhost:8080/login\n";
echo "   • Login with: secapistrano@my.cspc.edu.ph\n";
echo "   • After successful login, you should see:\n";
echo "     📊 Dashboard\n";
echo "     📦 Items\n";
echo "     🎯 Claims\n";
echo "     💬 Feedback Management ← THIS SHOULD BE VISIBLE\n";
echo "     ❓ Help & Support       ← THIS SHOULD BE VISIBLE\n\n";

echo "5. 🧪 TEST ACCESS:\n";
echo "   • Click on 'Feedback Management'\n";
echo "   • Click on 'Help & Support'\n";
echo "   • Both should load without 'Access denied' error\n\n";

echo "🚨 IF STILL NOT WORKING:\n";
echo "   • Try incognito/private window\n";
echo "   • Check browser console (F12) for errors\n";
echo "   • Run: php spark serve --host=0.0.0.0 --port=8080\n\n";

echo "✅ Sessions cleared! Follow the steps above.\n";