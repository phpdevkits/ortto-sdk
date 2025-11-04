<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use Tests\Factories\PersonSubscriptionDataFactory;

/**
 * @implements Arrayable<string, mixed>
 */
class PersonSubscriptionData implements Arrayable
{
    /**
     * Create a new factory instance.
     */
    public static function factory(): PersonSubscriptionDataFactory
    {
        return PersonSubscriptionDataFactory::new();
    }

    public function __construct(
        public ?string $email = null,
        public ?string $personId = null,
        public ?string $externalId = null,
        public ?bool $subscribed = null,
        public ?bool $smsOptedIn = null,
    ) {}

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->personId !== null) {
            $data['person_id'] = $this->personId;
        }

        if ($this->externalId !== null) {
            $data['external_id'] = $this->externalId;
        }

        if ($this->subscribed !== null) {
            $data['subscribed'] = $this->subscribed;
        }

        if ($this->smsOptedIn !== null) {
            $data['sms_opted_in'] = $this->smsOptedIn;
        }

        return $data;
    }
}
