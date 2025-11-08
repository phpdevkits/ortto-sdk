<?php

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Enums\Timeframe;
use PhpDevKits\Ortto\Requests\Person\ArchivePeople;
use PhpDevKits\Ortto\Requests\Person\DeletePeople;
use PhpDevKits\Ortto\Requests\Person\GetPeople;
use PhpDevKits\Ortto\Requests\Person\GetPeopleByIds;
use PhpDevKits\Ortto\Requests\Person\GetPeopleSubscriptions;
use PhpDevKits\Ortto\Requests\Person\GetPersonActivities;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use PhpDevKits\Ortto\Requests\Person\RestorePeople;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class PersonResource extends BaseResource
{
    /**
     * Access Person custom field operations
     */
    public function customField(): PersonCustomFieldResource
    {
        return new PersonCustomFieldResource(connector: $this->connector);
    }

    /**
     * @param  string[]|null  $inclusionIds
     * @param  string[]|null  $exclusionIds
     *
     * @throws Throwable
     */
    public function archive(
        ?array $inclusionIds = null,
        ?array $exclusionIds = null,
        bool $allRowsSelected = false,
    ): Response {
        return $this->connector->send(
            request: new ArchivePeople(
                inclusionIds: $inclusionIds,
                exclusionIds: $exclusionIds,
                allRowsSelected: $allRowsSelected,
            ),
        );
    }

    /**
     * @param  array<int, string>|null  $activities
     *
     * @throws Throwable
     */
    public function activities(
        string $personId,
        ?array $activities = null,
        ?int $limit = null,
        ?int $offset = null,
        string|Timeframe|null $timeframe = null,
    ): Response {
        return $this->connector->send(
            request: new GetPersonActivities(
                personId: $personId,
                activities: $activities,
                limit: $limit,
                offset: $offset,
                timeframe: $timeframe,
            ),
        );
    }

    /**
     * @param  string[]|null  $inclusionIds
     * @param  string[]|null  $exclusionIds
     *
     * @throws Throwable
     */
    public function delete(
        ?array $inclusionIds = null,
        ?array $exclusionIds = null,
        bool $allRowsSelected = false,
    ): Response {
        return $this->connector->send(
            request: new DeletePeople(
                inclusionIds: $inclusionIds,
                exclusionIds: $exclusionIds,
                allRowsSelected: $allRowsSelected,
            ),
        );
    }

    /**
     * @param  string[]  $fields
     * @param  array<string, mixed>|null  $filter
     *
     * @throws Throwable
     */
    public function get(
        array $fields,
        ?int $limit = 100,
        ?string $sortByFieldId = null,
        string|SortOrder|null $sortOrder = null,
        ?int $offset = null,
        ?string $cursorId = null,
        ?string $q = null,
        ?string $type = null,
        ?array $filter = null,
    ): Response {
        return $this->connector->send(
            request: new GetPeople(
                fields: $fields,
                limit: $limit,
                sortByFieldId: $sortByFieldId,
                sortOrder: $sortOrder,
                offset: $offset,
                cursorId: $cursorId,
                q: $q,
                type: $type,
                filter: $filter,
            ),
        );
    }

    /**
     * @param  string[]  $contactIds
     * @param  string[]|null  $fields
     *
     * @throws Throwable
     */
    public function getByIds(
        array $contactIds,
        ?array $fields = null,
    ): Response {
        return $this->connector->send(
            request: new GetPeopleByIds(
                contactIds: $contactIds,
                fields: $fields,
            ),
        );
    }

    /**
     * @param  array<int, array<string, string>>  $people
     *
     * @throws Throwable
     */
    public function subscriptions(
        array $people,
    ): Response {
        return $this->connector->send(
            request: new GetPeopleSubscriptions(
                people: $people,
            ),
        );
    }

    /**
     * @param  array<string, array<string, mixed>>  $people
     * @param  string[]  $mergeBy
     *
     * @throws Throwable
     */
    public function merge(
        array $people,
        array $mergeBy,
        int|MergeStrategy $mergeStrategy,
        int|FindStrategy $findStrategy,
        ?string $suppressionListFieldId = null,
        bool $skipNonExisting = false,
        bool $async = false,
        bool $skipSuppressionCheck = false
    ): Response {
        return $this->connector->send(
            request: new MergePeople(
                people: $people,
                mergeBy: $mergeBy,
                mergeStrategy: $mergeStrategy,
                findStrategy: $findStrategy,
                suppressionListFieldId: $suppressionListFieldId,
                skipNonExisting: $skipNonExisting,
                async: $async,
                skipSuppressionCheck: $skipSuppressionCheck,
            ),
        );
    }

    /**
     * @param  string[]|null  $inclusionIds
     * @param  string[]|null  $exclusionIds
     *
     * @throws Throwable
     */
    public function restore(
        ?array $inclusionIds = null,
        ?array $exclusionIds = null,
        bool $allRowsSelected = false,
    ): Response {
        return $this->connector->send(
            request: new RestorePeople(
                inclusionIds: $inclusionIds,
                exclusionIds: $exclusionIds,
                allRowsSelected: $allRowsSelected,
            ),
        );
    }
}
