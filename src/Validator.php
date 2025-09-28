<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\RoutedServerRequestValidator;
use Osteel\OpenApi\Testing\Adapters\MessageAdapterInterface;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;

final class Validator implements ValidatorInterface
{
    public function __construct(
        private RoutedServerRequestValidator $requestValidator,
        private ResponseValidator $responseValidator,
        private MessageAdapterInterface $adapter
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     * @param string $method  the HTTP method
     *
     * @throws ValidationException
     */
    public function validate(object $message, string $path, string $method): bool
    {
        $message = $this->adapter->convert($message);
        $operation = $this->getOperationAddress($path, $method);
        $validator = $message instanceof ResponseInterface ? $this->responseValidator : $this->requestValidator;

        try {
            $validator->validate($operation, $message);
        } catch (ValidationFailed $exception) {
            throw ValidationException::fromValidationFailed($exception);
        }

        return true;
    }

    /**
     * Build and return an OperationAddress object from the path and method.
     *
     * @param string $path   the OpenAPI path
     * @param string $method the HTTP method
     */
    private function getOperationAddress(string $path, string $method): OperationAddress
    {
        // Make sure the path begins with a forward slash.
        $path = sprintf('/%s', ltrim($path, '/'));

        return new OperationAddress($path, strtolower($method));
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function delete(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'delete');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function get(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'get');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function head(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'head');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function options(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'options');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function patch(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'patch');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function post(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'post');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function put(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'put');
    }

    /**
     * @inheritDoc
     *
     * @param object $message the HTTP message to validate
     * @param string $path    the OpenAPI path
     *
     * @throws ValidationException
     */
    public function trace(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'trace');
    }
}
