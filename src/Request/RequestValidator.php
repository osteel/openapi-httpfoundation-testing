<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Request;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ServerRequestValidator;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Osteel\OpenApi\Testing\Request\Adapters\RequestAdapterInterface;
use Osteel\OpenApi\Testing\ValidatorInterface;

/**
 * This class is a wrapper for League\OpenAPIValidation\PSR7\ServerRequestValidator objects,
 * providing an interface to validate HTTP requests against an OpenAPI definition.
 */
final class RequestValidator implements ValidatorInterface
{
    /**
     * @var ServerRequestValidator
     */
    private $validator;

    /**
     * @var RequestAdapterInterface
     */
    private $adapter;

    /**
     * Constructor.
     *
     * @param  ServerRequestValidator  $validator
     * @param  RequestAdapterInterface $adapter
     * @return void
     */
    public function __construct(ServerRequestValidator $validator, RequestAdapterInterface $adapter)
    {
        $this->validator = $validator;
        $this->adapter   = $adapter;
    }

    /**
     * Validate a request against the specified OpenAPI definition.
     *
     * @param  object $request The request object to validate.
     * @return bool
     * @throws ValidationException
     */
    public function validate(object $request, string $path = null, string $method = null): bool
    {
        try {
            $this->validator->validate($this->adapter->convert($request));
        } catch (ValidationFailed $exception) {
            throw ValidationException::fromValidationFailed($exception);
        }

        return true;
    }
}
