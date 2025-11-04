# Installation

## Requirements

Before installing Ortto SDK, ensure your system meets these requirements:

- **PHP**: 8.4 or higher
- **Laravel**: 10.x or 11.x
- **Composer**: Latest stable version
- **Ortto Account**: An active Ortto account with API access

## Install via Composer

Install the Ortto SDK package using Composer:

```bash
composer require phpdevkits/ortto-sdk
```

The package will automatically register its service provider through Laravel's package auto-discovery.

## Publish Configuration

Publish the configuration file to customize the SDK settings:

```bash
php artisan vendor:publish --provider="PhpDevKits\Ortto\OrttoServiceProvider"
```

This will create a `config/ortto.php` file in your Laravel application.

## Configure Environment Variables

Add your Ortto API credentials to your `.env` file:

```env
ORTTO_API_KEY=your-api-key-here
ORTTO_API_URL=https://api.eu.ap3api.com/v1
```

### Getting Your API Key

1. Log in to your [Ortto account](https://ortto.com)
2. Navigate to **Settings** → **API Keys**
3. Create a new API key or copy an existing one
4. Add the key to your `.env` file

### Choosing Your Region

Ortto uses region-specific endpoints. Set the `ORTTO_API_URL` based on your account region:

| Region | URL | Description |
|--------|-----|-------------|
| **Asia Pacific (Default)** | `https://api.eu.ap3api.com/v1` | For most Ortto users |
| **Australia** | `https://api.au1api.com/v1` | For AU region instances |
| **Europe** | `https://api.eu1api.com/v1` | For EU region instances |

::: tip
Not sure which region? Check your Ortto account URL. If it includes `.au.`, use the Australia endpoint. If it includes `.eu.`, use the Europe endpoint.
:::

## Verify Installation

Test your installation by creating a simple test route:

```php
use PhpDevKits\Ortto\Facades\Ortto;

Route::get('/test-ortto', function () {
    // This will create a test connector instance
    $connector = new \PhpDevKits\Ortto\Ortto();

    return [
        'status' => 'Ortto SDK installed successfully',
        'base_url' => $connector->resolveBaseUrl(),
    ];
});
```

Visit `/test-ortto` in your browser to confirm the SDK is properly installed.

## Next Steps

Now that you've installed Ortto SDK, you're ready to start integrating:

- [Quick Start Guide](/quickstart) - Get up and running in 5 minutes
- [Configuration](/configuration) - Learn about all configuration options
- [People Management](/resources/people) - Start managing your contacts

## Troubleshooting

### Class Not Found

If you encounter a "Class not found" error, try clearing Laravel's cache:

```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

### Configuration Not Loading

Ensure your `.env` file is properly formatted and the `ORTTO_API_KEY` variable is set. You can verify configuration is loading with:

```bash
php artisan tinker
>>> config('ortto.api_key')
```

### API Connection Issues

If you're having trouble connecting to the Ortto API:

1. Verify your API key is correct
2. Confirm you're using the correct regional endpoint
3. Check that your server can make outbound HTTPS connections
4. Review the [Ortto API Status](https://status.ortto.com/) page
