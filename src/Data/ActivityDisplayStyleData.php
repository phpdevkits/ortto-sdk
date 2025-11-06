<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class ActivityDisplayStyleData implements Arrayable
{
    /**
     * @param  string  $type  Display type: "activity", "activity_attribute", or "activity_template"
     * @param  string|null  $title  Custom format (required for "activity_template")
     * @param  string|null  $attributeName  Attribute to include (for "activity_attribute")
     * @param  string|null  $attributeFieldId  CDP field ID for the attribute
     */
    public function __construct(
        public string $type,
        public ?string $title = null,
        public ?string $attributeName = null,
        public ?string $attributeFieldId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'type' => $this->type,
        ];

        if ($this->title !== null) {
            $data['title'] = $this->title;
        }

        if ($this->attributeName !== null) {
            $data['attribute_name'] = $this->attributeName;
        }

        if ($this->attributeFieldId !== null) {
            $data['attribute_field_id'] = $this->attributeFieldId;
        }

        return $data;
    }
}
