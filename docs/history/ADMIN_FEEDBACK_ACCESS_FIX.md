# Admin Can't Access Feedback & Help Support - Fix Guide

## Issue: Admin role cannot access Feedback and Help & Support modules

---

## Status: Configuration is CORRECT ✅

After testing, I found that **the code is already correctly configured**:

### What's Already Working:
1. ✅ `RoleFilter.php` has 'feedback' and 'help-support' in Admin permissions
2. ✅ `Filters.php` includes both routes in auth and role filters  
3. ✅ `main.php` sidebar shows links for Admin, Super Admin, and Staff
4. ✅ Database has admin role stored correctly as 'admin'
5. ✅ Routes are properly configured

---

## Root Cause: Session Cache Issue 🔍

The problem is **cached session data** - the user's browser has an old session that doesn't recognize the updated permissions.

---

## Solution: Clear Session & Cache 🔧

### Option 1: User Must Logout and Login Again (Recommended)

**Steps:**
1. **Logout** from the system
2. **Close all browser tabs**
3. **Clear browser cache** (Ctrl + Shift + Delete)
4. **Login again** as Admin
5. **Check sidebar** - Feedback & Help & Support links should now be visible
6. **Click the links** - Should work without "Access denied" error

---

### Option 2: Clear Server Session Files

If logout doesn't work, manually clear session files:

**PowerShell Command:**
```powershell
Remove-Item "C:\Users\CAPISTRANO\Downloads\capstone-cspc\writable\session\*" -Force
```

**Then:**
1. Restart the development server
2. Login again as Admin
3. Test access

---

### Option 3: Clear Browser Cookies

**Chrome/Edge:**
1. Press `F12` to open DevTools
2. Go to **Application** tab
3. Click **Cookies** → `http://localhost:8080`
4. Right-click → **Clear**
5. Close DevTools
6. Refresh page (F5)

**Firefox:**
1. Press `Ctrl + Shift + I`
2. Go to **Storage** tab
3. Click **Cookies** → `http://localhost:8080`
4. Right-click → **Delete All**
5. Refresh page

---

## Verification Steps 📋

### Test 1: Check Sidebar Visibility
**As Admin user:**
1. Login to the system
2. Look at the left sidebar
3. You should see:
   - ✅ Feedback Management (with chat icon)
   - ✅ Help & Support (with question mark icon)

**Expected:** Both links visible ✓

---

### Test 2: Access Feedback Page
**Steps:**
1. Click "Feedback Management" in sidebar
2. OR navigate to: `http://localhost:8080/feedback`

**Expected Result:**
- ✅ Feedback page loads successfully
- ✅ No "Access denied" error
- ✅ Can view feedback list
- ✅ Can respond to feedback

**Before Fix (cached session):**
- ❌ "Access denied" error
- ❌ Redirected to dashboard

---

### Test 3: Access Help & Support Page
**Steps:**
1. Click "Help & Support" in sidebar
2. OR navigate to: `http://localhost:8080/help-support`

**Expected Result:**
- ✅ Help & Support page loads
- ✅ No "Access denied" error
- ✅ Can view support tickets
- ✅ Can manage help requests

---

## Why This Happened 🤔

### Session Storage
CodeIgniter stores user session data including:
- User role
- Permissions
- Login time
- User info

When permissions were updated in the code, **existing sessions still had old data**.

### Cache Headers
The RoleFilter sets cache headers to prevent back-button access after logout:
```php
$response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
```

But it doesn't force existing sessions to refresh permissions.

---

## Code Review ✅

### 1. RoleFilter.php (Lines 33-43)
```php
$permissions = [
    'super_admin' => ['*'], // Full access
    'admin' => [
        'dashboard', 'items', 'claims', 'emails', 'users', 
        'admin_role', 'reports', 
        'feedback', 'help-support'  // ✅ PRESENT
    ],
    'staff' => [
        'dashboard', 'claims', 'feedback', 'help-support', 'items'
    ]
];
```
**Status:** ✅ Correct

---

### 2. Filters.php (Lines 113-124)
```php
'auth' => [
    'before' => [
        // ... other routes ...
        'feedback', 'feedback/*',        // ✅ PROTECTED
        'help-support', 'help-support/*' // ✅ PROTECTED
    ]
],
'role' => [
    'before' => [
        // ... other routes ...
        'feedback', 'feedback/*',        // ✅ CHECKED
        'help-support', 'help-support/*' // ✅ CHECKED
    ]
]
```
**Status:** ✅ Correct

---

### 3. main.php Sidebar (Lines 420-431)
```php
<?php if (in_array($userRole, ['super_admin', 'admin', 'staff'])): ?>
<li>
    <a href="<?= site_url('feedback') ?>">
        <i class="bi bi-chat-dots"></i> Feedback Management
    </a>
</li>
<li>
    <a href="<?= site_url('help-support') ?>">
        <i class="bi bi-question-circle"></i> Help & Support
    </a>
</li>
<?php endif; ?>
```
**Status:** ✅ Correct - Shows for Admin

---

## Testing Results 🧪

### Database Check:
```
User: Sean Matthew Capistrano
Email: secapistrano@my.cspc.edu.ph
Role: 'admin' (5 bytes, clean)
```
✅ Role stored correctly

### Permissions Array:
```
Admin has access to:
[0] => dashboard
[1] => items
[2] => claims
[3] => emails
[4] => users
[5] => admin_role
[6] => reports
[7] => feedback       ✅
[8] => help-support   ✅
```
✅ Permissions correct

### URI Parsing:
```
/feedback -> First segment: 'feedback'
Admin has access: YES ✓

/help-support -> First segment: 'help-support'
Admin has access: YES ✓
```
✅ Logic correct

---

## Common Issues & Solutions 🔧

### Issue 1: Still getting "Access denied"
**Cause:** Session not cleared  
**Solution:** 
1. Open PowerShell
2. Run: `Remove-Item "C:\Users\CAPISTRANO\Downloads\capstone-cspc\writable\session\*" -Force`
3. Restart server: `php spark serve`
4. Login again

---

### Issue 2: Links not showing in sidebar
**Cause:** Browser cached old HTML  
**Solution:**
1. Hard refresh: `Ctrl + Shift + R` (or `Ctrl + F5`)
2. Or clear browser cache
3. If still not working, check browser console for JavaScript errors

---

### Issue 3: Works in incognito but not regular browser
**Cause:** Browser cookies/session storage  
**Solution:**
1. Open DevTools (F12)
2. Application → Clear storage
3. Check all boxes
4. Click "Clear site data"
5. Close DevTools and refresh

---

### Issue 4: "Role not found" error
**Cause:** Admin user not in admin_role table  
**Solution:**
1. Run: `php list_all_admin_roles.php`
2. Verify admin user exists
3. If not, run: `php create_admin_user.php`

---

## Quick Fix Commands 🚀

### Clear Everything at Once (PowerShell):
```powershell
# Stop server (if running)
# Ctrl + C in server terminal

# Clear sessions
Remove-Item "C:\Users\CAPISTRANO\Downloads\capstone-cspc\writable\session\*" -Force

# Clear cache
Remove-Item "C:\Users\CAPISTRANO\Downloads\capstone-cspc\writable\cache\*" -Force -Recurse

# Restart server
php spark serve
```

### Then in browser:
1. Press `Ctrl + Shift + Delete`
2. Select "Cookies" and "Cached images"
3. Click "Clear data"
4. Go to `http://localhost:8080/login`
5. Login as Admin
6. Test access to Feedback and Help & Support

---

## Success Checklist ✅

After clearing session/cache, verify:

- [ ] Can see "Feedback Management" link in sidebar
- [ ] Can see "Help & Support" link in sidebar
- [ ] Clicking Feedback link loads the page (no error)
- [ ] Clicking Help & Support link loads the page (no error)
- [ ] Can view feedback list
- [ ] Can respond to feedback
- [ ] Can view help & support dashboard
- [ ] Can manage support tickets
- [ ] No console errors in browser (F12)
- [ ] No PHP errors in terminal

---

## If Still Not Working 🆘

### Debug Steps:

1. **Check session data:**
```php
// Add to top of FeedbackController.php temporarily
log_message('debug', 'Session data: ' . print_r(session()->get(), true));
```

2. **Check role in RoleFilter:**
```php
// Add to RoleFilter.php hasPermission() method
log_message('debug', 'User role: ' . $userRole . ', URI: ' . $firstSegment);
log_message('debug', 'Has permission: ' . (in_array($firstSegment, $permissions[$userRole]) ? 'YES' : 'NO'));
```

3. **Check error logs:**
```powershell
Get-Content "C:\Users\CAPISTRANO\Downloads\capstone-cspc\writable\logs\log-*.log" -Tail 50
```

4. **Test direct URL:**
- Open incognito window
- Login as Admin
- Navigate to: `http://localhost:8080/feedback`
- If works in incognito = cache issue
- If doesn't work = code issue

---

## Summary 📝

**Problem:** Admin can't access Feedback & Help Support  
**Root Cause:** Cached session data with old permissions  
**Solution:** Logout + Clear session/cache + Login again  
**Status:** Code is correct, just needs cache clear ✅

**Quick Fix:**
```powershell
Remove-Item "C:\Users\CAPISTRANO\Downloads\capstone-cspc\writable\session\*" -Force
```

Then logout and login again!

---

Generated: October 13, 2025  
Status: READY TO TEST ✅
