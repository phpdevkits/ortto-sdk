<?php

namespace PhpDevKits\Ortto\Requests\Person;

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class MergePeople extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, array<string, mixed>>  $people
     * @param  string[]  $mergedBy
     */
    public function __construct(
        protected array $people,
        protected array $mergedBy,
        protected int|MergeStrategy $mergeStrategy,
        protected int|FindStrategy $findStrategy,
        protected string $suppressionListFieldId,
        protected bool $skipNonExisting = false,
        protected bool $async = false
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/merge';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'people' => $this->people,
            'merged_by' => $this->mergedBy,
            'merge_strategy' => is_int($this->mergeStrategy) ? $this->mergeStrategy : $this->mergeStrategy->value,
            'find_strategy' => is_int($this->findStrategy) ? $this->findStrategy : $this->findStrategy->value,
            'suppression_list_field_id' => $this->suppressionListFieldId,
            'skip_non_existing' => $this->skipNonExisting,
            'async' => $this->async,
        ];
    }
}
