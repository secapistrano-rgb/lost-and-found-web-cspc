# User Account Deletion Test Documentation

## Test Case Scenario
**Test for access to past data after user account deletion**

## Expected Result
Once a user account is deleted, all data associated with that user must also be inaccessible or erased.

## Test File Location
`tests/unit/UserDeletionTest.php`

## Overview
This test suite verifies that when a user account is deleted from the system, all associated data is properly handled according to GDPR and data privacy requirements. The tests ensure that deleted users cannot access the system and their personal data is no longer accessible.

## Database Schema Considerations

### Tables with User References

1. **users** (Primary table)
   - Contains core user account information
   - Primary key: `id`
   - Unique identifier: `email`

2. **user_settings** (CASCADE DELETE)
   - Foreign key: `user_id` → `users(id)` ON DELETE CASCADE
   - Automatically deleted when user is deleted

3. **items** (Data Retention for Audit)
   - Contains `reporter_id`, `reporter_email`, `reporter_name`
   - Items may be retained for historical/audit purposes
   - User should still be inaccessible even if items remain

4. **claims** (Data Retention)
   - Contains `claimant_id`, `google_user_id`
   - Claims may be retained for legal/audit purposes
   - User account should be inaccessible

5. **notifications** & **notifications_web**
   - Contains `user_email`
   - May be retained or cleaned up based on policy

6. **activity_logs**
   - Contains `user_id`, `user_email`, `user_name`
   - Should be retained for audit trail

7. **admin_role**
   - Contains `email`
   - May need manual cleanup or policy-based retention

## Test Cases

### Test 1: User Account Can Be Deleted
**Purpose**: Verify that a user account can be successfully deleted from the database.

**Steps**:
1. Create a test user
2. Delete the user using `UserModel::delete()`
3. Verify deletion returns true
4. Verify user no longer exists in database

**Expected Result**: User is successfully deleted and cannot be found.

---

### Test 2: User Data Is Inaccessible After Deletion
**Purpose**: Verify that deleted user data cannot be retrieved through normal channels.

**Steps**:
1. Create and then delete a test user
2. Attempt to retrieve user by ID
3. Attempt to retrieve user by email
4. Check user list

**Expected Result**: All retrieval methods return null/empty results.

---

### Test 3: User Settings Are Cascaded On Deletion
**Purpose**: Verify that user_settings are automatically deleted (CASCADE).

**Steps**:
1. Create user with settings
2. Delete the user
3. Check if user_settings still exist

**Expected Result**: User settings are automatically deleted due to CASCADE constraint.

---

### Test 4: Items Reported By Deleted User Are Handled
**Purpose**: Verify that items reported by a deleted user are handled appropriately.

**Steps**:
1. Create user and item reported by that user
2. Delete the user
3. Check if item still exists
4. Verify user is inaccessible

**Expected Result**: Items may remain for audit purposes, but user account is deleted.

---

### Test 5: Notifications For Deleted User Are Handled
**Purpose**: Verify notifications are handled when user is deleted.

**Steps**:
1. Create user with notifications
2. Delete the user
3. Verify user is inaccessible

**Expected Result**: User cannot be accessed even if notifications remain.

---

### Test 6: Activity Logs Record User Deletion
**Purpose**: Verify that user deletion is properly logged for audit purposes.

**Steps**:
1. Delete a user
2. Verify user is deleted from system

**Expected Result**: User is deleted and inaccessible (logs may remain for audit).

---

### Test 7: Claims By Deleted User Are Handled
**Purpose**: Verify claims made by deleted user are handled appropriately.

**Steps**:
1. Create user and claim by that user
2. Delete the user
3. Verify user is inaccessible

**Expected Result**: User account is inaccessible even if claims remain for legal purposes.

---

### Test 8: Admin Role Entries Are Handled On Deletion
**Purpose**: Verify admin_role entries are handled when user is deleted.

**Steps**:
1. Create user with admin role
2. Delete the user
3. Verify user is inaccessible

**Expected Result**: User account is deleted and inaccessible.

---

### Test 9: Multiple Deletion Attempts Handled Gracefully
**Purpose**: Verify that attempting to delete an already deleted user doesn't cause errors.

**Steps**:
1. Delete a user
2. Attempt to delete the same user again
3. Verify no exceptions are thrown

**Expected Result**: Second deletion returns false without errors.

---

### Test 10: Comprehensive Test - All User Data Is Inaccessible
**Purpose**: End-to-end test verifying complete user data inaccessibility after deletion.

**Steps**:
1. Create user with data across multiple tables
2. Delete the user
3. Verify user account is deleted
4. Verify cascaded data is deleted (user_settings)
5. Verify user cannot be retrieved by any method

**Expected Result**: User is completely deleted and inaccessible; cascaded data is removed.

---

## Running The Tests

### Run All User Deletion Tests
```bash
php vendor/bin/phpunit tests/unit/UserDeletionTest.php
```

### Run A Specific Test
```bash
php vendor/bin/phpunit tests/unit/UserDeletionTest.php --filter testUserAccountCanBeDeleted
```

### Run With Coverage Report
```bash
php vendor/bin/phpunit tests/unit/UserDeletionTest.php --coverage-html build/coverage
```

### Run With Detailed Output
```bash
php vendor/bin/phpunit tests/unit/UserDeletionTest.php --testdox
```

## Expected Test Results

All 10 tests should PASS, indicating:
- ✓ User accounts can be deleted
- ✓ Deleted user data is inaccessible
- ✓ Foreign key cascades work correctly (user_settings)
- ✓ Related data in other tables is handled appropriately
- ✓ No exceptions occur during deletion
- ✓ User privacy and data protection requirements are met

## Data Retention Policy Recommendations

Based on test findings:

1. **Immediate Deletion** (CASCADE):
   - `user_settings` - No retention needed

2. **Policy-Based Cleanup**:
   - `notifications_web` - Consider cleanup after 90 days
   - `admin_role` - Consider cleanup on user deletion

3. **Audit Trail Retention**:
   - `activity_logs` - Retain indefinitely for compliance
   - `items` - Retain for historical/audit purposes
   - `claims` - Retain for legal requirements

4. **Anonymization Consideration**:
   - For items, claims, and notifications, consider anonymizing user details
   - Replace with "Deleted User" or similar placeholder

## Security & Privacy Compliance

This test suite ensures compliance with:
- **GDPR** - Right to erasure (Right to be forgotten)
- **Data Privacy Act** - User data deletion requirements
- **Audit Requirements** - Proper logging of deletion actions

## Troubleshooting

### Common Issues

1. **Foreign Key Constraint Failures**
   - Check if all foreign keys have proper CASCADE rules
   - Review `lost_and_found.sql` for foreign key definitions

2. **Test Data Not Cleaned Up**
   - Ensure `tearDown()` method properly cleans test data
   - Check for orphaned records with `testuser_` prefix

3. **Tests Failing**
   - Verify database connection in test environment
   - Check if test database has same schema as production
   - Ensure proper permissions for test operations

## Implementation Checklist

- [x] Create comprehensive test suite
- [ ] Verify CASCADE rules in database schema
- [ ] Implement user deletion in `UserController::delete()`
- [ ] Add activity logging for user deletion
- [ ] Consider implementing soft delete option
- [ ] Add admin confirmation for user deletion
- [ ] Implement data export before deletion (GDPR requirement)
- [ ] Create cleanup script for orphaned related data
- [ ] Add user deletion feature to admin panel
- [ ] Document data retention policies

## Future Enhancements

1. **Soft Delete Option**
   - Add `deleted_at` column for soft deletes
   - Allow recovery within grace period

2. **Data Export Before Deletion**
   - Provide user with their data export
   - Required by GDPR

3. **Scheduled Cleanup**
   - Automated cleanup of related data
   - Policy-based retention management

4. **Anonymization**
   - Replace deleted user info with placeholders
   - Maintain data integrity for audit purposes

## Related Files

- Test File: `tests/unit/UserDeletionTest.php`
- User Model: `app/Models/UserModel.php`
- User Controller: `app/Controllers/UserController.php`
- Database Schema: `lost_and_found.sql`
- Migration Files: `temp_migrations/`

## Contact

For questions or issues with these tests, contact the development team or refer to the project documentation.
