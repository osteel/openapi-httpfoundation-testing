<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Exceptions;

use Exception;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;

class ValidationException extends Exception
{
    /**
     * Build a new exception from a ValidationFailed exception.
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

            if ($exception instanceof SchemaMismatch && ! empty($breadCrumb = $exception->dataBreadCrumb())) {
                $message .= sprintf(' Field: %s', implode('.', $breadCrumb->buildChain()));
            }
        }

        return new ValidationException($message, 0, $previous);
    }
}
