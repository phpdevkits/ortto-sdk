<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use PhpDevKits\Ortto\Enums\ActivityIcon;

/**
 * @implements Arrayable<string, mixed>
 */
class ActivityDefinitionData implements Arrayable
{
    /**
     * @param  string  $name  Unique activity identifier
     * @param  string|ActivityIcon  $iconId  Icon identifier
     * @param  bool|null  $trackConversionValue  Enable conversion value tracking
     * @param  bool|null  $touch  Update first/last seen timestamps
     * @param  bool|null  $filterable  Allow filter creation and reporting
     * @param  bool|null  $visibleInFeeds  Display in activity feeds
     * @param  array<string, mixed>|ActivityDisplayStyleData|null  $displayStyle  Display configuration
     * @param  array<int, array<string, mixed>|ActivityAttributeDefinitionData>|null  $attributes  Activity attributes
     */
    public function __construct(
        public string $name,
        public string|ActivityIcon $iconId,
        public ?bool $trackConversionValue = null,
        public ?bool $touch = null,
        public ?bool $filterable = null,
        public ?bool $visibleInFeeds = null,
        public array|ActivityDisplayStyleData|null $displayStyle = null,
        public ?array $attributes = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $iconId = $this->iconId instanceof ActivityIcon
            ? $this->iconId->value
            : $this->iconId;

        $data = [
            'name' => $this->name,
            'icon_id' => $iconId,
        ];

        if ($this->trackConversionValue !== null) {
            $data['track_conversion_value'] = $this->trackConversionValue;
        }

        if ($this->touch !== null) {
            $data['touch'] = $this->touch;
        }

        if ($this->filterable !== null) {
            $data['filterable'] = $this->filterable;
        }

        if ($this->visibleInFeeds !== null) {
            $data['visible_in_feeds'] = $this->visibleInFeeds;
        }

        if ($this->displayStyle !== null) {
            $data['display_style'] = $this->displayStyle instanceof ActivityDisplayStyleData
                ? $this->displayStyle->toArray()
                : $this->displayStyle;
        }

        if ($this->attributes !== null) {
            $data['attributes'] = array_map(
                fn (array|\PhpDevKits\Ortto\Data\ActivityAttributeDefinitionData $attr): array => $attr instanceof ActivityAttributeDefinitionData ? $attr->toArray() : $attr,
                $this->attributes
            );
        }

        return $data;
    }
}
