<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use Osteel\OpenApi\Testing\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * Validate a HTTP message against the specified OpenAPI definition.
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @param  string $method  The HTTP method.
     * @return bool
     * @throws ValidationException
     */
    public function validate(object $message, string $path, string $method): bool;
}
