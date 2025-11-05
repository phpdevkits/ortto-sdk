<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Requests\Activity\CreateActivities;
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
}
