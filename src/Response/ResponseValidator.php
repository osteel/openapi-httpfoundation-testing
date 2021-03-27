<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Response;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator as BaseResponseValidator;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Osteel\OpenApi\Testing\Response\Adapters\ResponseAdapterInterface;
use Osteel\OpenApi\Testing\ValidatorInterface;

/**
 * This class is a wrapper for League\OpenAPIValidation\PSR7\ResponseValidator objects,
 * providing an interface to validate HTTP responses against an OpenAPI definition.
 */
final class ResponseValidator implements ValidatorInterface
{
    /**
     * @var BaseResponseValidator
     */
    private $validator;

    /**
     * @var ResponseAdapterInterface
     */
    private $adapter;

    /**
     * Constructor.
     *
     * @param  BaseResponseValidator    $validator
     * @param  ResponseAdapterInterface $adapter
     * @return void
     */
    public function __construct(BaseResponseValidator $validator, ResponseAdapterInterface $adapter)
    {
        $this->validator = $validator;
        $this->adapter   = $adapter;
    }

    /**
     * Validate a response against the specified OpenAPI definition.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @param  string $method   The HTTP method.
     * @return bool
     * @throws ValidationException
     */
    public function validate(object $response, string $path, string $method): bool
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
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function get(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'get');
    }

    /**
     * Validate a response to a POST request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function post(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'post');
    }

    /**
     * Validate a response to a PUT request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function put(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'put');
    }

    /**
     * Validate a response to a PATCH request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function patch(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'patch');
    }

    /**
     * Validate a response to a DELETE request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function delete(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'delete');
    }

    /**
     * Validate a response to a HEAD request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function head(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'head');
    }

    /**
     * Validate a response to a OPTIONS request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function options(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'options');
    }

    /**
     * Validate a response to a TRACE request on the provided OpenAPI definition path.
     *
     * @param  object $response The response object to validate.
     * @param  string $path     The OpenAPI path.
     * @return boolean
     * @throws ValidationFailed
     */
    public function trace(object $response, string $path): bool
    {
        return $this->validate($response, $path, 'trace');
    }
}
