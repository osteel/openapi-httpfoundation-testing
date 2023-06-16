<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\RoutedServerRequestValidator;
use Osteel\OpenApi\Testing\Adapters\AdapterInterface;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Validator implements ValidatorInterface
{
    public function __construct(
        private RoutedServerRequestValidator $requestValidator,
        private ResponseValidator $responseValidator,
        private AdapterInterface $adapter
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     * @param string                                                    $method  the HTTP method
     *
     * @throws ValidationException
     */
    public function validate(
        Request|Response|ResponseInterface|ServerRequestInterface $message,
        string $path,
        string $method,
    ): bool {
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
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function delete(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'delete');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function get(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'get');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function head(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'head');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function options(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'options');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function patch(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'patch');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function post(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'post');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function put(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'put');
    }

    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function trace(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool
    {
        return $this->validate($message, $path, 'trace');
    }
}
