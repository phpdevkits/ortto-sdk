<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class CampaignPeriodData implements Arrayable
{
    public function __construct(
        public readonly int $year,
        public readonly int $month,
    ) {}

    /**
     * Create from CarbonImmutable instance
     */
    public static function fromCarbon(CarbonImmutable $date): self
    {
        return new self(
            year: $date->year,
            month: $date->month,
        );
    }

    /**
     * @return array{year: int, month: int}
     */
    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
        ];
    }
}
