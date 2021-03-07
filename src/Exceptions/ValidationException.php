<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Exceptions;

use Exception;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;

class ValidationException extends Exception
{
    /**
     * Build a new exception based on a ValidationFailed one.
     *
     * @param  ValidationFailed $exception
     * @return ValidationException
     */
    public static function fromValidationFailed(ValidationFailed $exception): ValidationException
    {
        $previous = $exception;
        $message  = $exception->getMessage();

        while ($exception = $exception->getPrevious()) {
            $message .= sprintf(': %s', $exception->getMessage());
        }

        return new ValidationException($message, 0, $previous);
    }
}
