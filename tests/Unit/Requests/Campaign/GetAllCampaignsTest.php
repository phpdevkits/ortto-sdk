<?php

use PhpDevKits\Ortto\Enums\CampaignSortField;
use PhpDevKits\Ortto\Enums\CampaignState;
use PhpDevKits\Ortto\Enums\CampaignType;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Campaign\GetAllCampaigns;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets all campaigns with no filters',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns);

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKey('campaigns')
            ->and($response->json())->toHaveKey('has_more');
    });

test('gets campaigns filtered by single type with enum',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_journey'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                type: CampaignType::Journey,
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns filtered by single type with string',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_email'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                type: 'email',
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns filtered by multiple types with enums',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_multiple_types'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                types: [CampaignType::Email, CampaignType::Sms, CampaignType::Journey],
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns filtered by state with enum',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_state_on'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                state: CampaignState::On,
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns filtered by state with string',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_state_draft'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                state: 'draft',
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns by folder id',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_by_folder'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                folderId: '6842c82de2f490232b196392',
            ));

        expect($response->status())->toBe(200)
            ->and($response->json())->toHaveKey('folder_id');
    });

test('gets specific campaigns by ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_by_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                campaignIds: ['6842c850c999be9c835e731a', '6842c850c999be9c835e731b'],
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns with pagination',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_paginated'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                limit: 5,
                offset: 10,
            ));

        expect($response->status())->toBe(200)
            ->and($response->json())->toHaveKey('next_offset');
    });

test('gets campaigns with search query',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_search'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                q: 'welcome',
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns with sorting by enum',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_sorted'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                sort: CampaignSortField::Name,
                sortOrder: SortOrder::Desc,
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns with sorting by string',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_sorted_string'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                sort: 'name',
                sortOrder: 'desc',
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns with mixed type filters',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_mixed_types'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                types: [CampaignType::Email, 'sms', CampaignType::Journey],
            ));

        expect($response->status())->toBe(200);
    });

test('gets campaigns with all parameters',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns_all_params'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAllCampaigns(
                type: CampaignType::Journey,
                state: CampaignState::On,
                folderId: '6842c82de2f490232b196392',
                limit: 5,
                offset: 0,
                q: 'welcome',
                sort: CampaignSortField::Name,
                sortOrder: SortOrder::Desc,
            ));

        expect($response->status())->toBe(200);
    });
