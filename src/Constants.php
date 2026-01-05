<?php

declare(strict_types=1);

namespace SimpleLicense\LicenseChecker;

/**
 * Constants for License Checker SDK
 * Minimal SDK for license validation and activation only
 * All values MUST come from constants - zero hardcoded values allowed
 */
final class Constants
{
    // HTTP Status Codes
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_CONFLICT = 409;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    // API Base Path
    public const API_BASE_PATH = '/api/v1';

    // Public License Endpoints (read-only and activation only)
    public const API_ENDPOINT_LICENSES_ACTIVATE = '/api/v1/licenses/activate';
    public const API_ENDPOINT_LICENSES_VALIDATE = '/api/v1/licenses/validate';
    public const API_ENDPOINT_LICENSES_GET = '/api/v1/licenses/%s';
    public const API_ENDPOINT_LICENSES_FEATURES = '/api/v1/licenses/%s/features';

    // License Status Values
    public const LICENSE_STATUS_ACTIVE = 'ACTIVE';
    public const LICENSE_STATUS_INACTIVE = 'INACTIVE';
    public const LICENSE_STATUS_EXPIRED = 'EXPIRED';
    public const LICENSE_STATUS_REVOKED = 'REVOKED';
    public const LICENSE_STATUS_SUSPENDED = 'SUSPENDED';

    // Error Codes
    public const ERROR_CODE_INVALID_FORMAT = 'INVALID_FORMAT';
    public const ERROR_CODE_INVALID_LICENSE_FORMAT = 'INVALID_LICENSE_FORMAT';
    public const ERROR_CODE_LICENSE_NOT_FOUND = 'LICENSE_NOT_FOUND';
    public const ERROR_CODE_LICENSE_INACTIVE = 'LICENSE_INACTIVE';
    public const ERROR_CODE_LICENSE_EXPIRED = 'LICENSE_EXPIRED';
    public const ERROR_CODE_ACTIVATION_LIMIT_EXCEEDED = 'ACTIVATION_LIMIT_EXCEEDED';
    public const ERROR_CODE_NOT_ACTIVATED_ON_DOMAIN = 'NOT_ACTIVATED_ON_DOMAIN';
    public const ERROR_CODE_VALIDATION_ERROR = 'VALIDATION_ERROR';

    // HTTP Headers
    public const HEADER_CONTENT_TYPE = 'Content-Type';
    public const HEADER_ACCEPT = 'Accept';

    // Content Types
    public const CONTENT_TYPE_JSON = 'application/json';

    // Default Configuration Values
    public const DEFAULT_TIMEOUT_SECONDS = 15;
    public const DEFAULT_CONNECT_TIMEOUT_SECONDS = 10;

    // Response Keys
    public const RESPONSE_KEY_SUCCESS = 'success';
    public const RESPONSE_KEY_DATA = 'data';
    public const RESPONSE_KEY_ERROR = 'error';
    public const RESPONSE_KEY_CODE = 'code';
    public const RESPONSE_KEY_MESSAGE = 'message';

    // Private constructor to prevent instantiation
    private function __construct()
    {
    }
}



