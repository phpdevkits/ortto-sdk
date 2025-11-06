<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use PhpDevKits\Ortto\Enums\ActivityDisplayType;

/**
 * @implements Arrayable<string, mixed>
 */
class ActivityAttributeDefinitionData implements Arrayable
{
    /**
     * @param  string  $name  Attribute identifier
     * @param  string|ActivityDisplayType  $displayType  Field type for this attribute
     * @param  string|null  $fieldId  CDP field mapping: empty string, "do-not-map", or field ID
     */
    public function __construct(
        public string $name,
        public string|ActivityDisplayType $displayType,
        public ?string $fieldId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $displayType = $this->displayType instanceof ActivityDisplayType
            ? $this->displayType->value
            : $this->displayType;

        $data = [
            'name' => $this->name,
            'display_type' => $displayType,
        ];

        if ($this->fieldId !== null) {
            $data['field_id'] = $this->fieldId;
        }

        return $data;
    }
}
