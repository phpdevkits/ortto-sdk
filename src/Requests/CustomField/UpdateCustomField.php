<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\CustomField;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateCustomField extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, string>|null  $replaceValues  Replace all options with these values
     * @param  array<int, string>|null  $addValues  Add these values to existing options
     * @param  array<int, string>|null  $removeValues  Remove these values from existing options
     */
    public function __construct(
        protected string $endpoint,
        protected string $fieldId,
        protected ?array $replaceValues = null,
        protected ?array $addValues = null,
        protected ?array $removeValues = null,
        protected ?bool $trackChanges = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->endpoint.'/update';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'field_id' => $this->fieldId,
        ];

        if ($this->replaceValues !== null) {
            $body['replace_values'] = $this->replaceValues;
        }

        if ($this->addValues !== null) {
            $body['add_values'] = $this->addValues;
        }

        if ($this->removeValues !== null) {
            $body['remove_values'] = $this->removeValues;
        }

        if ($this->trackChanges !== null) {
            $body['track_changes'] = $this->trackChanges;
        }

        return $body;
    }
}
