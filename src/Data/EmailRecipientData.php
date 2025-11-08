<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents a recipient for transactional emails.
 *
 * @implements Arrayable<string, mixed>
 */
class EmailRecipientData implements Arrayable
{
    /**
     * @param  array<string, mixed>  $fields  Person field data using Ortto field naming (e.g., str::email, str::first)
     * @param  array<string, mixed>|null  $location  Geographic location data
     * @param  array<string, mixed>|null  $asset  Per-recipient asset overrides for customized email content
     */
    public function __construct(
        public readonly array $fields,
        public readonly ?array $location = null,
        public readonly ?array $asset = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'fields' => $this->fields,
            'location' => $this->location,
            'asset' => $this->asset,
        ], fn (mixed $value): bool => $value !== null);
    }
}
