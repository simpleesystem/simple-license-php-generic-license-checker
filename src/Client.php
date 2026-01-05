<?php

declare(strict_types=1);

namespace SimpleLicense\LicenseChecker;

use SimpleLicense\LicenseChecker\Exceptions\ActivationLimitExceededException;
use SimpleLicense\LicenseChecker\Exceptions\ApiException;
use SimpleLicense\LicenseChecker\Exceptions\LicenseExpiredException;
use SimpleLicense\LicenseChecker\Exceptions\LicenseNotFoundException;
use SimpleLicense\LicenseChecker\Exceptions\NetworkException;
use SimpleLicense\LicenseChecker\Exceptions\ValidationException;
use SimpleLicense\LicenseChecker\Http\GuzzleHttpClient;
use SimpleLicense\LicenseChecker\Http\HttpClientInterface;

/**
 * Minimal License Checker Client
 * Handles only license validation, activation, and read-only license data access
 * No user interaction, no deactivation, no usage reporting
 */
class Client
{
    private HttpClientInterface $httpClient;
    private string $baseUrl;

    public function __construct(
        string $baseUrl,
        ?HttpClientInterface $httpClient = null,
        int $timeout = Constants::DEFAULT_TIMEOUT_SECONDS
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->httpClient = $httpClient ?? new GuzzleHttpClient($this->baseUrl, $timeout);
    }

    /**
     * Activate a license on a domain
     *
     * @param string $licenseKey License key
     * @param string $domain Domain name
     * @param string|null $siteName Optional site name
     * @return array<string, mixed> License data with activation and license objects
     * @throws ApiException
     */
    public function activateLicense(
        string $licenseKey,
        string $domain,
        ?string $siteName = null
    ): array {
        $data = [
            'license_key' => $licenseKey,
            'domain' => $domain,
        ];

        if ($siteName !== null) {
            $data['site_name'] = $siteName;
        }

        $response = $this->httpClient->post(Constants::API_ENDPOINT_LICENSES_ACTIVATE, $data);
        $parsed = $this->parseResponse($response);

        if (!isset($parsed[Constants::RESPONSE_KEY_SUCCESS]) || !$parsed[Constants::RESPONSE_KEY_SUCCESS]) {
            $this->handleErrorResponse($parsed, $response['status']);
        }

        return $parsed[Constants::RESPONSE_KEY_DATA] ?? [];
    }

    /**
     * Validate a license on a domain
     *
     * @param string $licenseKey License key
     * @param string $domain Domain name
     * @return array<string, mixed> License data
     * @throws ApiException
     */
    public function validateLicense(string $licenseKey, string $domain): array
    {
        $data = [
            'license_key' => $licenseKey,
            'domain' => $domain,
        ];

        $response = $this->httpClient->post(Constants::API_ENDPOINT_LICENSES_VALIDATE, $data);
        $parsed = $this->parseResponse($response);

        if (!isset($parsed[Constants::RESPONSE_KEY_SUCCESS]) || !$parsed[Constants::RESPONSE_KEY_SUCCESS]) {
            $this->handleErrorResponse($parsed, $response['status']);
        }

        return $parsed[Constants::RESPONSE_KEY_DATA] ?? [];
    }

    /**
     * Get license data by key (read-only)
     *
     * @param string $licenseKey License key
     * @return array<string, mixed> License data
     * @throws LicenseNotFoundException
     * @throws ApiException
     */
    public function getLicenseData(string $licenseKey): array
    {
        $url = sprintf(Constants::API_ENDPOINT_LICENSES_GET, $licenseKey);
        $response = $this->httpClient->get($url);
        $parsed = $this->parseResponse($response);

        if (!isset($parsed[Constants::RESPONSE_KEY_SUCCESS]) || !$parsed[Constants::RESPONSE_KEY_SUCCESS]) {
            $errorCode = $parsed[Constants::RESPONSE_KEY_ERROR][Constants::RESPONSE_KEY_CODE] ?? '';
            if ($errorCode === Constants::ERROR_CODE_LICENSE_NOT_FOUND) {
                throw new LicenseNotFoundException(
                    $parsed[Constants::RESPONSE_KEY_ERROR][Constants::RESPONSE_KEY_MESSAGE] ?? 'License not found',
                    $errorCode
                );
            }
            $this->handleErrorResponse($parsed, $response['status']);
        }

        return $parsed[Constants::RESPONSE_KEY_DATA] ?? [];
    }

    /**
     * Get license features/entitlements (read-only)
     *
     * @param string $licenseKey License key
     * @return array<string, mixed> Features/entitlements data
     * @throws ApiException
     */
    public function getLicenseFeatures(string $licenseKey): array
    {
        $url = sprintf(Constants::API_ENDPOINT_LICENSES_FEATURES, $licenseKey);
        $response = $this->httpClient->get($url);
        $parsed = $this->parseResponse($response);

        if (!isset($parsed[Constants::RESPONSE_KEY_SUCCESS]) || !$parsed[Constants::RESPONSE_KEY_SUCCESS]) {
            $this->handleErrorResponse($parsed, $response['status']);
        }

        return $parsed[Constants::RESPONSE_KEY_DATA] ?? [];
    }

    /**
     * Handle error responses and throw appropriate exceptions
     *
     * @param array<string, mixed> $parsed Parsed response data
     * @param int $statusCode HTTP status code
     * @throws ApiException
     */
    private function handleErrorResponse(array $parsed, int $statusCode): void
    {
        $errorCode = $parsed[Constants::RESPONSE_KEY_ERROR][Constants::RESPONSE_KEY_CODE] ?? Constants::ERROR_CODE_VALIDATION_ERROR;
        $errorMessage = $parsed[Constants::RESPONSE_KEY_ERROR][Constants::RESPONSE_KEY_MESSAGE] ?? 'API error';

        if ($errorCode === Constants::ERROR_CODE_LICENSE_EXPIRED) {
            throw new LicenseExpiredException($errorMessage, $errorCode);
        }

        if ($errorCode === Constants::ERROR_CODE_ACTIVATION_LIMIT_EXCEEDED) {
            throw new ActivationLimitExceededException($errorMessage, $errorCode);
        }

        if ($errorCode === Constants::ERROR_CODE_LICENSE_NOT_FOUND) {
            throw new LicenseNotFoundException($errorMessage, $errorCode);
        }

        if ($statusCode === Constants::HTTP_BAD_REQUEST) {
            throw new ValidationException($errorMessage, $errorCode);
        }

        throw new ApiException($errorMessage, $errorCode, $parsed[Constants::RESPONSE_KEY_ERROR] ?? null);
    }

    /**
     * Parse API response
     *
     * @param array{status: int, body: string, headers: array<string, string>} $response HTTP response
     * @return array<string, mixed> Parsed response data
     * @throws ApiException
     */
    private function parseResponse(array $response): array
    {
        $body = $response['body'];

        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                'Invalid JSON response from server',
                Constants::ERROR_CODE_VALIDATION_ERROR,
                ['body' => $body]
            );
        }

        return $data;
    }
}



