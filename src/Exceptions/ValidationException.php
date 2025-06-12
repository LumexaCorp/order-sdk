<?php

declare(strict_types=1);

namespace Lumexa\OrderSdk\Exceptions;

class ValidationException extends OrderException
{
    /**
     * @param array<string, array<string>> $errors
     */
    public function __construct(
        string $message,
        private readonly array $errors,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string, array<string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
