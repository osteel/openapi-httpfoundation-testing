<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ValidatorInterface
{
    /**
     * Validate a HTTP message against the specified OpenAPI definition.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     * @param string                                                    $method  the HTTP method
     *
     * @throws ValidationException
     */
    public function validate(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path, string $method): bool;

    /**
     * Validate a HTTP message for a DELETE operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function delete(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a GET operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function get(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a HEAD operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function head(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a OPTIONS operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function options(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a PATCH operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function patch(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a POST operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function post(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a PUT operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function put(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;

    /**
     * Validate a HTTP message for a TRACE operation on the provided OpenAPI definition path.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to validate
     * @param string                                                    $path    the OpenAPI path
     *
     * @throws ValidationFailed
     */
    public function trace(Request|Response|ResponseInterface|ServerRequestInterface $message, string $path): bool;
}
