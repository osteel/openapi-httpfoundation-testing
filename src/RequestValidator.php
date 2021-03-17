<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\RequestValidator as BaseRequestValidator;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;

/**
 * This class is a wrapper for League\OpenAPIValidation\PSR7\RequestValidator objects,
 * providing an interface to validate HTTP requests against an OpenAPI definition.
 */
final class RequestValidator
{
    /**
     * @var \League\OpenAPIValidation\PSR7\RequestValidator
     */
    private $validator;

    /**
     * @var RequestAdapter
     */
    private $adapter;

    /**
     * Constructor.
     *
     * @param  \League\OpenAPIValidation\PSR7\RequestValidator $validator
     * @param  RequestAdapter                                  $adapter
     * @return void
     */
    public function __construct(BaseRequestValidator $validator, RequestAdapter $adapter)
    {
        $this->validator = $validator;
        $this->adapter   = $adapter;
    }

    /**
     * Validate a request against the specified OpenAPI definition.
     *
     * @param  object $request The request object to validate.
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(object $request): bool
    {
        try {
            $this->validator->validate($this->adapter->convert($request));
        } catch (ValidationFailed $exception) {
            throw ValidationException::fromValidationFailed($exception);
        }

        return true;
    }
}
