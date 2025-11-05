<?php

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Data\CustomFieldData;
use PhpDevKits\Ortto\Requests\CustomField\CreateCustomField;
use PhpDevKits\Ortto\Requests\CustomField\GetCustomFields;
use PhpDevKits\Ortto\Requests\CustomField\UpdateCustomField;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Abstract base class for custom field resources
 *
 * Provides shared functionality for Person and Account custom field operations
 */
abstract class CustomFieldResource extends BaseResource
{
    /**
     * Get the base endpoint for custom field operations
     */
    abstract protected function getBaseEndpoint(): string;

    /**
     * Create a new custom field
     *
     * @param  array<string, mixed>|CustomFieldData  $field
     */
    public function create(
        CustomFieldData|array $field,
    ): Response {
        /** @var array<string, mixed> $data */
        $data = $field instanceof CustomFieldData ? $field->toArray() : $field;

        return $this->connector->send(
            request: new CreateCustomField(
                endpoint: $this->getBaseEndpoint(),
                data: $data,
            ),
        );
    }

    /**
     * Get all custom fields
     */
    public function get(): Response
    {
        return $this->connector->send(
            request: new GetCustomFields(
                endpoint: $this->getBaseEndpoint(),
            ),
        );
    }

    /**
     * Update an existing custom field
     *
     * @param  array<int, string>|null  $replaceValues  Replace all options with these values
     * @param  array<int, string>|null  $addValues  Add these values to existing options
     * @param  array<int, string>|null  $removeValues  Remove these values from existing options
     */
    public function update(
        string $fieldId,
        ?array $replaceValues = null,
        ?array $addValues = null,
        ?array $removeValues = null,
        ?bool $trackChanges = null,
    ): Response {
        return $this->connector->send(
            request: new UpdateCustomField(
                endpoint: $this->getBaseEndpoint(),
                fieldId: $fieldId,
                replaceValues: $replaceValues,
                addValues: $addValues,
                removeValues: $removeValues,
                trackChanges: $trackChanges,
            ),
        );
    }
}
