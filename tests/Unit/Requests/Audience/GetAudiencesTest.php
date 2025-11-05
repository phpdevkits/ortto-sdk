<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Audience\GetAudiences;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets all audiences',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/get_audiences_all'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAudiences(
                    limit: 40,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray()
            ->and($response->json())
            ->not->toBeEmpty();

    });

test('gets audiences with search term',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/get_audiences_with_search'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAudiences(
                    searchTerm: 'subscribers',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();

    });

test('gets audiences with filter included',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/get_audiences_with_filter'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAudiences(
                    withFilter: true,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();

    });

test('gets audiences with pagination',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/get_audiences_paginated'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAudiences(
                    limit: 10,
                    offset: 0,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();

    });

test('gets archived audiences',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/get_audiences_archived'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAudiences(
                    archived: true,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->body())
            ->toBeString();

    });

test('gets retention audiences',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/get_audiences_retention'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAudiences(
                    retention: true,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->body())
            ->toBeString();

    });
