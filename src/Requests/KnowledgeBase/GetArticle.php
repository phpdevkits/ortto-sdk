<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\KnowledgeBase;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetArticle extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $id  Article unique identifier
     */
    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/kb/get-one-article';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
