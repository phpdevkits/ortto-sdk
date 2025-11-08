<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Campaign;

use PhpDevKits\Ortto\Data\CampaignPeriodData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetCampaignCalendar extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  CampaignPeriodData|array{year: int, month: int}  $start  Start period (year and month)
     * @param  CampaignPeriodData|array{year: int, month: int}  $end  End period (year and month)
     * @param  string  $timezone  Timezone for campaign list (e.g., "Australia/Sydney")
     */
    public function __construct(
        protected CampaignPeriodData|array $start,
        protected CampaignPeriodData|array $end,
        protected string $timezone,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/campaign/calendar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'start' => $this->start instanceof CampaignPeriodData ? $this->start->toArray() : $this->start,
            'end' => $this->end instanceof CampaignPeriodData ? $this->end->toArray() : $this->end,
            'timezone' => $this->timezone,
        ];
    }
}
