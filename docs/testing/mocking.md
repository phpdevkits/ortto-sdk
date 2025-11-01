# Mocking API Responses

## Overview

When testing your Ortto integration, you should mock API responses to avoid hitting the real Ortto API. Saloon provides excellent built-in mocking capabilities that make this straightforward.

This guide shows you how to use [Saloon's MockClient](https://docs.saloon.dev/the-basics/testing) to mock Ortto API responses in your tests.

## Basic Mocking

### Mock a Single Response

Use Saloon's `MockClient` and `MockResponse` to fake API responses:

```php
use PhpDevKits\Ortto\Ortto;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('can create connector with mock', function () {
    // Create a mock client with a fake response
    $mock = new MockClient([
        MockResponse::make(['status' => 'success'], 200),
    ]);

    // Create connector and attach mock
    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Now any requests will use the mocked response
});
```

### Mock with JSON Response

Most Ortto API responses are JSON:

```php
test('handles JSON response', function () {
    $mock = new MockClient([
        MockResponse::make([
            'people' => [
                [
                    'email' => 'john@example.com',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ]
            ]
        ], 200),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Your test assertions here
});
```

### Mock with Status Codes

Test different HTTP status codes:

```php
test('handles 404 not found', function () {
    $mock = new MockClient([
        MockResponse::make(['error' => 'Not found'], 404),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Test error handling
});

test('handles 429 rate limit', function () {
    $mock = new MockClient([
        MockResponse::make(['error' => 'Rate limit exceeded'], 429),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Test rate limit handling
});
```

## Sequential Responses

Mock multiple responses that return in sequence:

```php
test('handles multiple sequential requests', function () {
    $mock = new MockClient([
        MockResponse::make(['id' => 1], 200),  // First request
        MockResponse::make(['id' => 2], 200),  // Second request
        MockResponse::make(['id' => 3], 200),  // Third request
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Make multiple requests - each gets the next response
});
```

## URL-Based Mocking

Mock specific responses based on the request URL:

```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('mocks different endpoints', function () {
    $mock = new MockClient([
        'api.eu.ap3api.com/v1/people/*' => MockResponse::make(['people' => []], 200),
        'api.eu.ap3api.com/v1/activities/*' => MockResponse::make(['activities' => []], 200),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Requests to /people/* will get the people response
    // Requests to /activities/* will get the activities response
});
```

## Method-Based Mocking

Mock responses based on HTTP method:

```php
test('mocks based on HTTP method', function () {
    $mock = new MockClient([
        MockResponse::make(['success' => true], 200)->forMethod('POST'),
        MockResponse::make(['data' => []], 200)->forMethod('GET'),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // POST requests get the first response
    // GET requests get the second response
});
```

## Fixture-Based Mocking

Use fixture files for complex responses:

```php
test('uses fixture for response', function () {
    // Create a fixture file: tests/Fixtures/person-response.json
    $fixture = file_get_contents(__DIR__ . '/../Fixtures/person-response.json');

    $mock = new MockClient([
        MockResponse::fixture('person-response'),
        // Or with full path
        // MockResponse::make(json_decode($fixture, true), 200),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);
});
```

## Asserting Requests Were Made

Verify that requests were sent:

```php
test('asserts requests were made', function () {
    $mock = new MockClient([
        MockResponse::make(['status' => 'success'], 200),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Make your request here
    // $connector->send(new YourRequest());

    // Assert that a request was recorded
    $mock->assertSent(function ($request) {
        return $request->getMethod() === 'POST';
    });

    // Assert request count
    $mock->assertSentCount(1);
});
```

## Real-World Examples

### Testing Person Merge

```php
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\People\MergePeopleRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('can merge person successfully', function () {
    // Mock successful response
    $mock = new MockClient([
        MockResponse::make([
            'status' => 'success',
            'people_merged' => 1,
        ], 200),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Create and send the request
    $request = new MergePeopleRequest([
        'email' => 'john@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $response = $connector->send($request);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('status', 'success')
        ->and($response->json())->toHaveKey('people_merged', 1);

    // Assert the request was sent
    $mock->assertSent(function ($request) {
        return $request instanceof MergePeopleRequest
            && $request->body()->all()['email'] === 'john@example.com';
    });
});
```

### Testing Error Handling

```php
test('handles API errors gracefully', function () {
    // Mock error response
    $mock = new MockClient([
        MockResponse::make([
            'error' => 'Invalid API key',
        ], 401),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Test your error handling
    expect(fn() => $connector->send(new MergePeopleRequest(['email' => 'test@example.com'])))
        ->toThrow(\Saloon\Exceptions\Request\RequestException::class);
});
```

### Testing Rate Limiting

```php
test('handles rate limiting', function () {
    // First request succeeds, second is rate limited
    $mock = new MockClient([
        MockResponse::make(['status' => 'success'], 200),
        MockResponse::make(['error' => 'Rate limit exceeded'], 429),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    $request = new MergePeopleRequest(['email' => 'test@example.com']);

    // First request succeeds
    $response1 = $connector->send($request);
    expect($response1->status())->toBe(200);

    // Second request is rate limited
    expect(fn() => $connector->send($request))
        ->toThrow(\Saloon\Exceptions\Request\RequestException::class);
});
```

## Testing in Your Application

When testing your Laravel application that uses Ortto SDK:

```php
use PhpDevKits\Ortto\Ortto;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('syncs user to Ortto on registration', function () {
    // Mock Ortto response
    $mock = new MockClient([
        MockResponse::make(['status' => 'success'], 200),
    ]);

    // You'll need to bind the mocked connector to the container
    app()->singleton(Ortto::class, function () use ($mock) {
        $connector = new Ortto();
        $connector->withMockClient($mock);
        return $connector;
    });

    // Register a user
    $response = $this->post('/register', [
        'email' => 'test@example.com',
        'first_name' => 'Test',
        'last_name' => 'User',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');

    // Assert the Ortto API was called
    $mock->assertSent(function ($request) {
        return str_contains($request->getUrl(), 'people');
    });
});
```

## Best Practices

### Always Mock in Tests

Never hit the real API during automated tests:

```php
// ✅ Good - uses mocking
test('creates person', function () {
    $mock = new MockClient([
        MockResponse::make(['status' => 'success'], 200),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);
    // ...
});

// ❌ Bad - hits real API
test('creates person', function () {
    $connector = new Ortto(); // No mock!
    // This will make a real API call
});
```

### Use Realistic Response Data

Make your mocks match real Ortto API responses:

```php
// ✅ Good - realistic response structure
$mock = new MockClient([
    MockResponse::make([
        'people' => [
            [
                'email' => 'john@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'tags' => [],
                'custom_fields' => [],
            ]
        ],
        'status' => 'success',
    ], 200),
]);
```

### Test Error Scenarios

Don't just test the happy path:

```php
test('handles network errors', function () {
    $mock = new MockClient([
        MockResponse::make(['error' => 'Network timeout'], 504),
    ]);

    $connector = new Ortto();
    $connector->withMockClient($mock);

    // Test error handling
});
```

## Additional Resources

- [Saloon Testing Documentation](https://docs.saloon.dev/the-basics/testing)
- [Saloon MockClient Reference](https://docs.saloon.dev/the-basics/testing#mocking-requests)
- [Architecture Tests](architecture-tests.md) - Testing with Lawman
- [Testing Overview](overview.md) - General testing guide

## Next Steps

- [Testing Overview](overview.md) - Learn about the testing philosophy
- [Architecture Tests](architecture-tests.md) - Test Saloon architecture with Lawman
- [Saloon Docs](https://docs.saloon.dev/the-basics/testing) - Official Saloon testing guide
