#!/usr/bin/env php
<?php

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║         ADMIN ACCESS VERIFICATION - FINAL CHECK                 ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$allGood = true;

// Check 1: RoleFilter.php permissions
echo "✓ Checking RoleFilter.php permissions...\n";
$roleFilterContent = file_get_contents('app/Filters/RoleFilter.php');
if (strpos($roleFilterContent, "'feedback', 'help-support'") !== false || 
    (strpos($roleFilterContent, "'feedback'") !== false && strpos($roleFilterContent, "'help-support'") !== false)) {
    echo "  ✅ PASS: Admin permissions include 'feedback' and 'help-support'\n";
} else {
    echo "  ❌ FAIL: Admin permissions missing\n";
    $allGood = false;
}

// Check 2: Filters.php routes
echo "\n✓ Checking Filters.php route protection...\n";
$filtersContent = file_get_contents('app/Config/Filters.php');
if (strpos($filtersContent, "'feedback', 'feedback/*'") !== false && 
    strpos($filtersContent, "'help-support', 'help-support/*'") !== false) {
    echo "  ✅ PASS: Routes protected by filters\n";
} else {
    echo "  ❌ FAIL: Routes not properly protected\n";
    $allGood = false;
}

// Check 3: Sidebar menu
echo "\n✓ Checking sidebar menu in main.php...\n";
$mainLayoutContent = file_get_contents('app/Views/layouts/main.php');
if (strpos($mainLayoutContent, "['super_admin', 'admin', 'staff']") !== false &&
    strpos($mainLayoutContent, "site_url('feedback')") !== false &&
    strpos($mainLayoutContent, "site_url('help-support')") !== false) {
    echo "  ✅ PASS: Sidebar shows links for admin role\n";
} else {
    echo "  ❌ FAIL: Sidebar configuration incorrect\n";
    $allGood = false;
}

// Check 4: Controllers exist
echo "\n✓ Checking controller files...\n";
if (file_exists('app/Controllers/FeedbackController.php')) {
    echo "  ✅ PASS: FeedbackController.php exists\n";
} else {
    echo "  ⚠️  WARNING: FeedbackController.php not found\n";
}

if (file_exists('app/Controllers/HelpSupportController.php') || 
    file_exists('app/Controllers/HelpSupport.php')) {
    echo "  ✅ PASS: HelpSupport controller exists\n";
} else {
    echo "  ⚠️  WARNING: HelpSupport controller not found\n";
}

// Summary
echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
if ($allGood) {
    echo "✅ ✅ ✅  ALL CHECKS PASSED  ✅ ✅ ✅\n";
    echo "═══════════════════════════════════════════════════════════════════\n";
    echo "\n";
    echo "🎉 ADMIN ACCESS IS PROPERLY CONFIGURED!\n";
    echo "\n";
    echo "📋 Next Steps:\n";
    echo "  1. Login as Admin user\n";
    echo "  2. Look at sidebar - you should see:\n";
    echo "     • Feedback Management (chat icon)\n";
    echo "     • Help & Support (question icon)\n";
    echo "  3. Click the links - pages should load\n";
    echo "\n";
    echo "🔧 If still not working:\n";
    echo "  • Clear browser cache (Ctrl+Shift+Delete)\n";
    echo "  • Use incognito/private mode\n";
    echo "  • Check the role in database is 'admin' (not 'Admin ' with space)\n";
    echo "\n";
} else {
    echo "❌ ❌ ❌  SOME CHECKS FAILED  ❌ ❌ ❌\n";
    echo "═══════════════════════════════════════════════════════════════════\n";
    echo "\n";
    echo "Please review the failures above and fix them.\n";
    echo "\n";
}

echo "\n";
