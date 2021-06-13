<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\RoutedServerRequestValidator;
use Osteel\OpenApi\Testing\Adapters\AdapterInterface;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;

final class Validator implements ValidatorInterface
{
    /**
     * @var RoutedServerRequestValidator
     */
    private $requestValidator;

    /**
     * @var ResponseValidator
     */
    private $responseValidator;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Constructor.
     *
     * @param  RoutedServerRequestValidator $requestValidator
     * @param  ResponseValidator            $responseValidator
     * @param  AdapterInterface             $adapter
     * @return void
     */
    public function __construct(
        RoutedServerRequestValidator $requestValidator,
        ResponseValidator $responseValidator,
        AdapterInterface $adapter
    ) {
        $this->requestValidator  = $requestValidator;
        $this->responseValidator = $responseValidator;
        $this->adapter           = $adapter;
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @param  string $method  The HTTP method.
     * @return bool
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function validate(object $message, string $path, string $method): bool
    {
        $message   = $this->adapter->convert($message);
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
     * @param  string $path   The OpenAPI path.
     * @param  string $method The HTTP method.
     * @return OperationAddress
     */
    private function getOperationAddress(string $path, string $method): OperationAddress
    {
        // Make sure the path begins with a forward slash.
        $path = sprintf('/%s', ltrim($path, '/'));

        return new OperationAddress($path, strtolower($method));
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function delete(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'delete');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function get(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'get');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function head(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'head');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function options(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'options');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function patch(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'patch');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function post(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'post');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function put(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'put');
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $message The HTTP message to validate.
     * @param  string $path    The OpenAPI path.
     * @return bool
     * @throws ValidationFailed
     */
    public function trace(object $message, string $path): bool
    {
        return $this->validate($message, $path, 'trace');
    }
}
