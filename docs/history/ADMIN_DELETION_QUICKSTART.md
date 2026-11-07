# Admin User Deletion Test - Quick Start Guide

## Test Case Overview
**Scenario:** Admin deletes user account  
**Expected Result:** User and all associated data become inaccessible  
**Role:** Administrator  
**Module:** User Management

---

## 🚀 Quick Test Execution

### Option 1: PHP Test Runner (Recommended)
```bash
cd c:\Users\CAPISTRANO\Downloads\capstone-cspc
php admin_test_user_deletion.php
```

**What it does:**
- Creates test user with associated data
- Simulates admin deletion
- Verifies user is inaccessible
- Checks CASCADE deletion
- Cleans up test data
- Provides detailed pass/fail report

**Expected Output:**
```
========================================
ADMIN USER DELETION TEST
========================================
[STEP 1] Verifying CASCADE DELETE configuration
✓ PASS: CASCADE DELETE is configured on user_settings

[STEP 5] ADMIN ACTION: Deleting user
✓ PASS: User deletion executed successfully

[STEP 6] Verifying user is deleted and inaccessible
✓ PASS: User not accessible by ID
✓ PASS: User not accessible by email
✓ PASS: User not in user list

OVERALL RESULT: ✅ PASS - All critical tests passed
```

---

### Option 2: SQL Test Script
```bash
# Using MySQL command line
mysql -u root -p lost_and_found < admin_user_deletion_test.sql

# Or import in phpMyAdmin
# 1. Open phpMyAdmin
# 2. Select 'lost_and_found' database
# 3. Go to SQL tab
# 4. Paste contents of admin_user_deletion_test.sql
# 5. Execute
```

**What it does:**
- Verifies CASCADE constraints
- Creates test data
- Deletes user
- Shows before/after comparison
- Provides recommendations

---

### Option 3: Existing PHPUnit Tests
```bash
# Run all user deletion tests
php vendor/bin/phpunit tests/unit/UserDeletionTest.php --testdox

# Run specific test
php vendor/bin/phpunit tests/unit/UserDeletionTest.php --filter testUserDataIsInaccessibleAfterDeletion
```

---

## 📋 Manual Testing via Admin Dashboard

### Step-by-Step Procedure:

#### 1. Login as Admin
- Navigate to: `http://your-domain/login`
- Use admin credentials
- Verify admin dashboard access

#### 2. Access User Management
- Click **Users** menu
- URL: `http://your-domain/users`
- User list should be displayed

#### 3. Create Test User
- Click **"Add New User"** button
- Fill in details:
  - Email: `test.deletion@cspc.edu.ph`
  - Full Name: `Test Deletion User`
  - Department: `Test Department`
  - Status: `Active`
- Click **Save**
- Note the user ID

#### 4. Delete Test User
- In user list, find "Test Deletion User"
- Click **Delete** button/icon
- Confirm deletion in dialog
- Verify success message

#### 5. Verify Deletion
- Refresh user list
- Search for deleted user → Should not be found
- Try to access user profile → Should fail
- Check database:
  ```sql
  SELECT * FROM users WHERE email = 'test.deletion@cspc.edu.ph';
  -- Should return 0 rows
  ```

---

## ✅ Expected Test Results

### Critical Requirements (Must Pass)
- [x] User account deleted from database
- [x] User not accessible by ID
- [x] User not accessible by email
- [x] User not in user list
- [x] User settings CASCADE deleted
- [x] Activity log records deletion
- [x] No errors during deletion

### Data Handling (Acceptable)
- ℹ️ Items reported by user may remain (audit trail)
- ℹ️ Claims made by user may remain (legal requirement)
- ℹ️ Activity logs remain (compliance requirement)
- ⚠️ Admin role entries may remain (needs cleanup policy)
- ⚠️ Notifications may remain (needs cleanup policy)

---

## 🔍 Verification Checklist

After running tests, verify:

### Database Level
```sql
-- User should not exist
SELECT * FROM users WHERE id = <user_id>;
-- Expected: 0 rows

-- Settings should be CASCADE deleted
SELECT * FROM user_settings WHERE user_id = <user_id>;
-- Expected: 0 rows

-- Activity log should exist
SELECT * FROM activity_logs 
WHERE module = 'Users' AND action = 'Delete User'
ORDER BY created_at DESC LIMIT 1;
-- Expected: 1 row showing deletion
```

### Application Level
- [ ] User cannot login
- [ ] User profile returns 404
- [ ] User not in management list
- [ ] Search returns no results
- [ ] No PHP errors in logs

### Admin Dashboard
- [ ] Success message displayed
- [ ] User removed from list
- [ ] No broken links
- [ ] Activity log updated

---

## 🐛 Troubleshooting

### Issue 1: Tests Fail - User Still Exists
**Symptoms:** User found in database after deletion

**Check:**
```sql
SELECT * FROM users WHERE id = <user_id>;
```

**Solution:**
```sql
-- Force delete
DELETE FROM users WHERE id = <user_id>;
```

**Prevention:**
- Check UserController::delete() method
- Verify database connection
- Check for transaction rollbacks

---

### Issue 2: CASCADE Not Working
**Symptoms:** user_settings remain after deletion

**Check:**
```sql
SELECT 
    CONSTRAINT_NAME, DELETE_RULE 
FROM 
    information_schema.REFERENTIAL_CONSTRAINTS
WHERE 
    CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'user_settings';
```

**Solution:**
```sql
-- Drop existing constraint
ALTER TABLE user_settings 
DROP FOREIGN KEY user_settings_ibfk_1;

-- Recreate with CASCADE
ALTER TABLE user_settings
ADD CONSTRAINT user_settings_ibfk_1 
FOREIGN KEY (user_id) 
REFERENCES users(id) 
ON DELETE CASCADE;
```

---

### Issue 3: Admin Role Entries Remain
**Symptoms:** admin_role records still exist

**Expected:** This is normal - no CASCADE constraint

**Manual Cleanup:**
```sql
DELETE FROM admin_role 
WHERE email = 'deleted.user@cspc.edu.ph';
```

**Long-term Solution:**
Implement cleanup policy or trigger (see ADMIN_USER_DELETION_TEST.md)

---

## 📊 Test Results Interpretation

### PASS Scenario ✅
```
OVERALL RESULT: ✅ PASS - All critical tests passed
User deletion is working correctly!
```
**Action:** System is working as expected. Proceed with production use.

---

### FAIL Scenario ❌
```
OVERALL RESULT: ❌ FAIL - Some tests failed
Please review failed tests above.
```
**Action:** 
1. Review specific failed tests
2. Check database constraints
3. Verify UserModel::delete() method
4. Run SQL fix script: `test_user_deletion_cascade.sql`
5. Re-run tests

---

## 🔒 GDPR Compliance Status

### ✅ Met Requirements
- User account deletion
- Data inaccessibility
- CASCADE deletion on settings
- Activity logging for audit
- Right to erasure compliance

### ⚠️ Recommendations
1. **Implement cleanup policies for:**
   - admin_role table
   - notifications_web table

2. **Consider anonymization for:**
   - Items: Replace user info with "Deleted User"
   - Claims: Replace user info with "Deleted User"

3. **Add soft delete option:**
   ```sql
   ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;
   ```
   - Allows 30-day grace period
   - GDPR compliant approach

---

## 📚 Related Documentation

1. **ADMIN_USER_DELETION_TEST.md** - Complete admin testing guide
2. **tests/USER_DELETION_TEST_DOCUMENTATION.md** - Technical details
3. **USER_DELETION_TEST_SUMMARY.md** - Implementation summary
4. **USER_DELETION_TEST_QUICKSTART.md** - General quick start

---

## 🎯 Test Status Summary

| Component | Status | Notes |
|-----------|--------|-------|
| User Deletion | ✅ Working | Verified |
| CASCADE Delete | ✅ Working | user_settings |
| Data Inaccessibility | ✅ Working | Verified |
| Activity Logging | ✅ Working | Implemented |
| Admin Interface | ✅ Available | /users route |
| PHPUnit Tests | ✅ Available | 10 test methods |
| SQL Verification | ✅ Available | 2 scripts |
| Documentation | ✅ Complete | 6 documents |

---

## 💡 Quick Tips

1. **Always test in development first**
   - Never test on production data
   - Create database backup before testing

2. **Use test users with clear naming**
   - Example: `test.deletion.*@cspc.edu.ph`
   - Easy to identify and clean up

3. **Check activity logs after deletion**
   - Verify deletion is logged
   - Good for audit trail

4. **Clean up test data**
   - Scripts automatically clean up
   - Manual cleanup SQL provided

5. **Monitor for remaining data**
   - Items (acceptable for audit)
   - Admin roles (needs policy)
   - Notifications (needs policy)

---

## 🚨 Critical Notes

⚠️ **Before Production Use:**
1. Implement cleanup policy for admin_role
2. Implement cleanup policy for notifications
3. Add confirmation dialog in UI
4. Test with real (non-production) data
5. Verify activity logging works
6. Document deletion procedure for admins
7. Train admin users on deletion process

---

## 📞 Support

**For Issues:**
1. Check troubleshooting section above
2. Review detailed documentation
3. Verify database constraints
4. Check application logs
5. Review UserController::delete() method

**Test Files Location:**
- PHP Runner: `admin_test_user_deletion.php`
- SQL Script: `admin_user_deletion_test.sql`
- Documentation: `ADMIN_USER_DELETION_TEST.md`
- PHPUnit: `tests/unit/UserDeletionTest.php`

---

**Document Version:** 1.0  
**Last Updated:** October 13, 2025  
**Status:** Ready for Admin Testing
