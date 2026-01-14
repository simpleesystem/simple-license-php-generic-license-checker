<?php

declare(strict_types=1);

namespace SimpleLicense\LicenseChecker\Exceptions;

use RuntimeException;

/**
 * Base exception for all API-related errors
 */
class ApiException extends RuntimeException
{
    protected string $errorCode;
    /**
     * @var array<string, mixed>|null
     */
    protected ?array $errorDetails;

    /**
     * @param array<string, mixed>|null $errorDetails
     */
    public function __construct(
        string $message,
        string $errorCode = '',
        ?array $errorDetails = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->errorDetails = $errorDetails;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getErrorDetails(): ?array
    {
        return $this->errorDetails;
    }
}



