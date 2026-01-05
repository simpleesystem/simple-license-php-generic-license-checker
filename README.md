# Simple License System - License Checker SDK

Minimal PHP SDK for license validation and activation only. This lightweight SDK provides the absolute minimum needed for checking license validity, expiry, and entitlements. Perfect for simple integrations that only need to verify licenses without any user management or advanced features.

## Features

- **Minimal footprint** - Only essential license checking methods
- **Read-only license access** - Get license data and features/entitlements
- **License activation** - Activate licenses on domains (write operation)
- **License validation** - Validate licenses on domains (read operation)
- **No user interaction** - No user management features
- **No deactivation** - No usage reporting or other write operations
- Type-safe with PHP 8.1+ strict types

## Installation

```bash
composer require simple-license/license-checker
```

## Quick Start

```php
<?php

use SimpleLicense\LicenseChecker\Client;

$client = new Client('https://your-license-server.com');

// Activate a license
$licenseData = $client->activateLicense(
    'your-license-key',
    'example.com',
    'My Application'
);

// Validate a license
$licenseData = $client->validateLicense(
    'your-license-key',
    'example.com'
);

// Get license data (read-only)
$license = $client->getLicenseData('your-license-key');

// Get license features/entitlements (read-only)
$features = $client->getLicenseFeatures('your-license-key');
```

## API Coverage

This minimal SDK only includes essential license checking methods:

### License Activation
- `activateLicense()` - Activate a license on a domain (write operation)

### License Validation
- `validateLicense()` - Validate a license on a domain (read operation)

### License Data (Read-Only)
- `getLicenseData()` - Get license information by key
- `getLicenseFeatures()` - Get license features/entitlements by key

## What's NOT Included

This SDK intentionally excludes:
- ❌ License deactivation
- ❌ Usage reporting
- ❌ Update checking
- ❌ User management
- ❌ Any other write operations beyond activation

For full functionality, use the `simple-license/client-sdk` package instead.

## Error Handling

The SDK throws specific exceptions for different error conditions:

- `LicenseExpiredException` - License has expired
- `ActivationLimitExceededException` - Activation limit reached
- `LicenseNotFoundException` - License not found
- `ValidationException` - Request validation failed
- `NetworkException` - Network/HTTP errors
- `ApiException` - General API errors

```php
use SimpleLicense\LicenseChecker\Exceptions\LicenseExpiredException;

try {
    $client->validateLicense($key, $domain);
} catch (LicenseExpiredException $e) {
    // Handle expired license
} catch (\SimpleLicense\LicenseChecker\Exceptions\ApiException $e) {
    // Handle other API errors
}
```

## Configuration

```php
$client = new Client(
    baseUrl: 'https://your-license-server.com',
    httpClient: null, // Optional custom HTTP client
    timeout: 15 // Optional timeout in seconds
);
```

## Requirements

- PHP 8.1+
- Guzzle HTTP Client 7.5+ (for generic version)

## License

MIT



