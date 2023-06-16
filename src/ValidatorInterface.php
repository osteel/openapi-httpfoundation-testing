<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * Validate a HTTP message against the specified OpenAPI definition.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     * @param string $method  the HTTP method
     *
     * @throws ValidationException
     */
    public function validate(object $message, string $path, string $method): bool;

    /**
     * Validate a HTTP message for a DELETE operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function delete(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a GET operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function get(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a HEAD operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function head(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a OPTIONS operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function options(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a PATCH operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function patch(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a POST operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function post(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a PUT operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function put(object $message, string $path): bool;

    /**
     * Validate a HTTP message for a TRACE operation on the provided OpenAPI definition path.
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function trace(object $message, string $path): bool;
}
