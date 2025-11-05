<?php

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use PhpDevKits\Ortto\Enums\CustomFieldScope;
use PhpDevKits\Ortto\Enums\CustomFieldType;

/**
 * @implements Arrayable<string, mixed>
 */
class CustomFieldData implements Arrayable
{
    /**
     * @param  array<int, string>|null  $options  Options for single_select or multi_select fields
     */
    public function __construct(
        public string $name,
        public string|CustomFieldType $type,
        public string|CustomFieldScope $scope,
        public ?string $fieldId = null,
        public ?bool $trackChanges = null,
        public ?array $options = null,
    ) {}

    /**
     * @return array{
     *     name: string,
     *     type: string,
     *     scope: string,
     *     field_id?: string,
     *     track_changes?: bool,
     *     options?: array<int, string>
     * }
     */
    public function toArray(): array
    {
        $type = $this->type instanceof CustomFieldType ? $this->type->value : $this->type;
        $scope = $this->scope instanceof CustomFieldScope ? $this->scope->value : $this->scope;

        $data = [
            'name' => $this->name,
            'type' => $type,
            'scope' => $scope,
        ];

        if ($this->fieldId !== null) {
            $data['field_id'] = $this->fieldId;
        }

        if ($this->trackChanges !== null) {
            $data['track_changes'] = $this->trackChanges;
        }

        if ($this->options !== null) {
            $data['options'] = $this->options;
        }

        return $data;
    }
}
