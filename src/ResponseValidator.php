<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator as BaseResponseValidator;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;

/**
 * This class is a wrapper for League\OpenAPIValidation\PSR7\ResponseValidator objects,
 * providing an interface to validate HTTP responses against an OpenAPI definition.
 */
final class ResponseValidator
{
    /**
     * @var \League\OpenAPIValidation\PSR7\ResponseValidator
     */
    private $validator;

    /**
     * @var ResponseAdapter
     */
    private $adapter;

    /**
     * Constructor.
     *
     * @param  \League\OpenAPIValidation\PSR7\ResponseValidator $validator
     * @param  ResponseAdapter                                  $adapter
     * @return void
     */
    public function __construct(BaseResponseValidator $validator, ResponseAdapter $adapter)
    {
        $this->validator = $validator;
        $this->adapter   = $adapter;
    }

    /**
     * Validate a response against the specified OpenAPI definition.
     *
     * @param  string $path     The OpenAPI path.
     * @param  string $method   The HTTP method.
     * @param  object $response The response object to validate.
     * @return bool
     * @throws ValidationException
     */
    public function validate(string $path, string $method, object $response): bool
    {
        // Make sure the path begins with a forward slash.
        $path = sprintf('/%s', ltrim($path, '/'));

        $operation = new OperationAddress($path, strtolower($method));

        try {
            $this->validator->validate($operation, $this->adapter->convert($response));
        } catch (ValidationFailed $exception) {
            throw ValidationException::fromValidationFailed($exception);
        }

        return true;
    }

    /**
     * Validate a response to a GET request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function get(string $path, object $response): bool
    {
        return $this->validate($path, 'get', $response);
    }

    /**
     * Validate a response to a POST request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function post(string $path, object $response): bool
    {
        return $this->validate($path, 'post', $response);
    }

    /**
     * Validate a response to a PUT request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function put(string $path, object $response): bool
    {
        return $this->validate($path, 'put', $response);
    }

    /**
     * Validate a response to a PATCH request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function patch(string $path, object $response): bool
    {
        return $this->validate($path, 'patch', $response);
    }

    /**
     * Validate a response to a DELETE request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function delete(string $path, object $response): bool
    {
        return $this->validate($path, 'delete', $response);
    }

    /**
     * Validate a response to a HEAD request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function head(string $path, object $response): bool
    {
        return $this->validate($path, 'head', $response);
    }

    /**
     * Validate a response to a OPTIONS request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function options(string $path, object $response): bool
    {
        return $this->validate($path, 'options', $response);
    }

    /**
     * Validate a response to a TRACE request on the provided OpenAPI definition path.
     *
     * @param  string $path     The OpenAPI path.
     * @param  object $response The response object to validate.
     * @return boolean
     * @throws \League\OpenAPIValidation\PSR7\Exception\ValidationFailed
     */
    public function trace(string $path, object $response): bool
    {
        return $this->validate($path, 'trace', $response);
    }
}
