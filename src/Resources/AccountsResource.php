<?php

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Requests\Accounts\AddContactsToAccount;
use PhpDevKits\Ortto\Requests\Accounts\ArchiveAccounts;
use PhpDevKits\Ortto\Requests\Accounts\DeleteAccounts;
use PhpDevKits\Ortto\Requests\Accounts\GetAccounts;
use PhpDevKits\Ortto\Requests\Accounts\GetAccountsByIds;
use PhpDevKits\Ortto\Requests\Accounts\MergeAccounts;
use PhpDevKits\Ortto\Requests\Accounts\RemoveContactsFromAccount;
use PhpDevKits\Ortto\Requests\Accounts\RestoreAccounts;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class AccountsResource extends BaseResource
{
    /**
     * Associate one or more contacts (people) with an account.
     * Each account can have up to 3,000 associated contacts.
     *
     * @param  string  $accountId  Account ID to add contacts to
     * @param  string[]  $personIds  Person IDs to associate with the account
     *
     * @throws Throwable
     */
    public function addContacts(string $accountId, array $personIds): Response
    {
        return $this->connector->send(
            request: new AddContactsToAccount(
                accountId: $accountId,
                personIds: $personIds,
            ),
        );
    }

    /**
     * Archive one or more accounts (organizations).
     *
     * @param  string[]  $accountIds  Account IDs to archive
     *
     * @throws Throwable
     */
    public function archive(array $accountIds): Response
    {
        return $this->connector->send(
            request: new ArchiveAccounts(
                accountIds: $accountIds,
            ),
        );
    }

    /**
     * Permanently delete one or more archived accounts.
     * Accounts must be archived before deletion.
     *
     * @param  string[]  $accountIds  Archived account IDs to delete
     *
     * @throws Throwable
     */
    public function delete(array $accountIds): Response
    {
        return $this->connector->send(
            request: new DeleteAccounts(
                accountIds: $accountIds,
            ),
        );
    }

    /**
     * Retrieve accounts with optional filtering, sorting, and pagination.
     *
     * @param  string[]  $fields  Account field IDs to retrieve (required, max 100)
     * @param  int|null  $limit  Number of accounts to return (max 100)
     * @param  int|null  $offset  Offset for pagination
     * @param  string|null  $cursorId  Cursor ID for cursor-based pagination
     * @param  string|null  $sortByFieldId  Field ID to sort by
     * @param  string|SortOrder|null  $sortOrder  Sort direction (asc/desc)
     * @param  array<string, mixed>|null  $filter  Filter criteria
     * @param  string|null  $q  Search query string
     * @param  string|null  $type  Filter by account type (e.g., 'archived_account')
     * @param  string[]|null  $inclusionIds  Include only these account IDs
     * @param  string[]|null  $exclusionIds  Exclude these account IDs
     *
     * @throws Throwable
     */
    public function get(
        array $fields,
        ?int $limit = null,
        ?int $offset = null,
        ?string $cursorId = null,
        ?string $sortByFieldId = null,
        string|SortOrder|null $sortOrder = null,
        ?array $filter = null,
        ?string $q = null,
        ?string $type = null,
        ?array $inclusionIds = null,
        ?array $exclusionIds = null
    ): Response {
        return $this->connector->send(
            request: new GetAccounts(
                limit: $limit,
                offset: $offset,
                cursorId: $cursorId,
                sortByFieldId: $sortByFieldId,
                sortOrder: $sortOrder,
                fields: $fields,
                filter: $filter,
                q: $q,
                type: $type,
                inclusionIds: $inclusionIds,
                exclusionIds: $exclusionIds,
            ),
        );
    }

    /**
     * Retrieve specific accounts by account identifiers.
     *
     * @param  string[]  $accountIds  Account IDs to retrieve (required)
     * @param  string[]  $fields  Account field IDs to retrieve (required, max 20)
     *
     * @throws Throwable
     */
    public function getByIds(array $accountIds, array $fields): Response
    {
        return $this->connector->send(
            request: new GetAccountsByIds(
                accountIds: $accountIds,
                fields: $fields,
            ),
        );
    }

    /**
     * Create or update one or more organizations (accounts).
     *
     * @param  array<int, array<string, mixed>>  $accounts  Array of account records (1-100 max)
     * @param  string[]  $mergeBy  Field IDs specifying which account fields determine create vs. update logic
     * @param  int|MergeStrategy  $mergeStrategy  Controls how existing values merge (1=Append, 2=Overwrite [default], 3=Ignore)
     * @param  int|FindStrategy  $findStrategy  For dual merge fields: 0=Any match, 1=First field only
     *
     * @throws Throwable
     */
    public function merge(
        array $accounts,
        array $mergeBy,
        int|MergeStrategy $mergeStrategy = MergeStrategy::OverwriteExisting,
        int|FindStrategy $findStrategy = FindStrategy::Any,
        bool $async = false
    ): Response {
        return $this->connector->send(
            request: new MergeAccounts(
                accounts: $accounts,
                mergeBy: $mergeBy,
                mergeStrategy: $mergeStrategy,
                findStrategy: $findStrategy,
                async: $async,
            ),
        );
    }

    /**
     * Remove the association between one or more contacts and an account.
     * This removes links to the contacts but does not delete them.
     *
     * @param  string  $accountId  Account ID to remove contacts from
     * @param  string[]  $personIds  Person IDs to disassociate from the account
     *
     * @throws Throwable
     */
    public function removeContacts(string $accountId, array $personIds): Response
    {
        return $this->connector->send(
            request: new RemoveContactsFromAccount(
                accountId: $accountId,
                personIds: $personIds,
            ),
        );
    }

    /**
     * Restore one or more archived accounts.
     *
     * @param  string[]  $accountIds  Account IDs to restore
     *
     * @throws Throwable
     */
    public function restore(array $accountIds): Response
    {
        return $this->connector->send(
            request: new RestoreAccounts(
                accountIds: $accountIds,
            ),
        );
    }
}
