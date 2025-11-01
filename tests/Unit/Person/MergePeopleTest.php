<?php

use Illuminate\Support\Str;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\MergePeople;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('merge people on ortto',
    /**
     * @throws Throwable
     */
    function (): void {

        $response = $this->ortto->send(
            new MergePeople(
                people: [
                    ['fields' => [
                        'str::ei' => Str::uuid()->toString(),
                        'str::email' => fake()->email,
                        'str::first' => $first = fake()->firstName(),
                        'str::last' => $last = fake()->lastName(),
                        'str::name' => "$first $last",
                    ]],
                ],
                mergedBy: ['str::email'],
                mergeStrategy: MergeStrategy::OverwriteExisting->value,
                findStrategy: FindStrategy::All->value,
                suppressionListFieldId: 'str::email'
            ),
        );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray();

    });
