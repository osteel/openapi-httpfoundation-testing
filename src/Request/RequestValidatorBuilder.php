<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Request;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Osteel\OpenApi\Testing\AbstractValidatorBuilder;
use Osteel\OpenApi\Testing\Request\Adapters\HttpFoundationRequestAdapter;
use Osteel\OpenApi\Testing\Request\Adapters\RequestAdapterInterface;
use Osteel\OpenApi\Testing\Request\RequestValidator;
use Osteel\OpenApi\Testing\ValidatorBuilderInterface;
use Osteel\OpenApi\Testing\ValidatorInterface;

/**
 * This class creates RequestValidator objects based on OpenAPI definitions.
 */
class RequestValidatorBuilder extends AbstractValidatorBuilder implements ValidatorBuilderInterface
{
    /**
     * @var string
     */
    protected $adapter = HttpFoundationRequestAdapter::class;

    /**
     * Create a RequestValidator object based on an OpenAPI definition.
     *
     * @param  string $method     The ValidatorBuilder object's method to use.
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    protected static function fromMethod(string $method, string $definition): ValidatorBuilderInterface
    {
        $builder = (new ValidatorBuilder())->$method($definition);

        return new RequestValidatorBuilder($builder);
    }

    /**
     * Return the validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return new RequestValidator($this->validatorBuilder->getServerRequestValidator(), new $this->adapter());
    }

    /**
     * Change the request adapter to use. The provided class must implement
     * \Osteel\OpenApi\Testing\Request\RequestAdapterInterface.
     *
     * @param  string $class The adapter's class.
     * @return ValidatorBuilderInterface
     * @throws InvalidArgumentException
     */
    public function setAdapter(string $class): ValidatorBuilderInterface
    {
        if (! is_subclass_of($class, RequestAdapterInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class %s does not implement the %s interface',
                $class,
                RequestAdapterInterface::class
            ));
        }

        $this->adapter = $class;

        return $this;
    }
}
