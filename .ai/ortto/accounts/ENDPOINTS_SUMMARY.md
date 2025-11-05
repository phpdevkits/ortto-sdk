# Ortto Accounts API Endpoints Summary

## Important Note: URL Discrepancy

The Ortto API documentation shows some endpoints using `/organizations/` in the URL path, but Ortto renamed "Organizations" to "Accounts" in June 2025. The actual endpoint paths may use either:
- `/v1/accounts/...` (newer, preferred)
- `/v1/organizations/...` (legacy, may still work)

**Recommendation:** Test both URL formats when implementing.

## Documented Endpoints

### 1. Retrieve Accounts (GET with filters)
- **Method:** POST
- **Path:** `/v1/accounts/get`
- **Status:** ✅ Documented
- **Documentation:** `get.md`
- **Features:** Filtering, sorting, pagination, search

### 2. Retrieve Accounts by IDs
- **Method:** POST
- **Path:** `/v1/accounts/get-by-ids`
- **Status:** ✅ Documented
- **Documentation:** `get-by-ids.md`
- **Features:** Direct ID lookup

### 3. Create/Update Accounts (Merge)
- **Method:** POST
- **Path:** `/v1/accounts/merge`
- **Status:** ✅ Implemented
- **SDK:** `src/Requests/Accounts/MergeAccounts.php`
- **Documentation:** `merge.md`

### 4. Archive Accounts
- **Method:** PUT
- **Path:** `/v1/organizations/archive` (may also be `/v1/accounts/archive`)
- **Status:** ✅ Documented
- **Documentation:** `archive.md`
- **Note:** Must archive before delete

### 5. Restore Archived Accounts
- **Method:** PUT
- **Path:** `/v1/organizations/restore` (may also be `/v1/accounts/restore`)
- **Status:** ✅ Documented
- **Documentation:** `restore.md`

### 6. Delete Archived Accounts
- **Method:** DELETE
- **Path:** `/v1/organizations/delete` (may also be `/v1/accounts/delete`)
- **Status:** ✅ Documented
- **Documentation:** `delete.md`
- **Requirement:** Must be archived first

### 7. Add Contacts to Account
- **Method:** PUT
- **Path:** `/v1/accounts/contacts/add`
- **Status:** ✅ Documented
- **Documentation:** `contacts-add.md`
- **Limit:** Max 3,000 people per account

### 8. Remove Contacts from Account
- **Method:** PUT
- **Path:** `/v1/accounts/contacts/remove`
- **Status:** ✅ Documented
- **Documentation:** `contacts-remove.md`

### 9. Get Instance Schema
- **Method:** POST
- **Path:** `/instance-schema/get`
- **Status:** ✅ Implemented
- **SDK:** `src/Requests/Account/GetAccountSchema.php`
- **Documentation:** `../account/instance-schema-get.md`
- **Note:** Uses "Account" (singular) namespace

## Implementation Priority

**Completed:**
1. ✅ Instance schema retrieval
2. ✅ Merge (create/update) accounts

**Next Priority:**
1. Get accounts (with filters) - Most commonly used
2. Get accounts by IDs - Simple ID lookup
3. Archive/Restore/Delete - Standard CRUD operations
4. Contact management - Association features

## Regional Endpoints

All endpoints support three regions:
- **Default (AP3):** `https://api.ap3api.com/`
- **Australia:** `https://api.au.ap3api.com/`
- **Europe:** `https://api.eu.ap3api.com/`
