<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Data\ActivityDefinitionData;
use PhpDevKits\Ortto\Requests\Activity\CreateActivities;
use PhpDevKits\Ortto\Requests\Activity\CreateActivityDefinition;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class ActivityResource extends BaseResource
{
    /**
     * Create custom activity events for contacts.
     *
     * Activities can be used to track customer behavior, events, and interactions.
     * You can create/update contacts while associating activities, or associate
     * activities with existing contacts using their person_id.
     *
     * @param  array<int, array<string, mixed>>  $activities  Array of activity records (1-100 max)
     * @param  bool  $async  Process activities asynchronously for bulk operations
     *
     * @throws Throwable
     */
    public function create(array $activities, bool $async = false): Response
    {
        return $this->connector->send(
            request: new CreateActivities(
                activities: $activities,
                async: $async,
            ),
        );
    }

    /**
     * Create a custom activity definition.
     *
     * Activity definitions define the schema and behavior of custom activity types
     * before you can create activity events using them.
     *
     * @param  array<string, mixed>|ActivityDefinitionData  $definition  Activity definition data
     *
     * @throws Throwable
     */
    public function createDefinition(array|ActivityDefinitionData $definition): Response
    {
        return $this->connector->send(
            request: new CreateActivityDefinition(
                definition: $definition,
            ),
        );
    }
}
