<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Response;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Osteel\OpenApi\Testing\AbstractValidatorBuilder;
use Osteel\OpenApi\Testing\Response\Adapters\HttpFoundationResponseAdapter;
use Osteel\OpenApi\Testing\Response\Adapters\ResponseAdapterInterface;
use Osteel\OpenApi\Testing\Response\ResponseValidator;
use Osteel\OpenApi\Testing\ValidatorBuilderInterface;
use Osteel\OpenApi\Testing\ValidatorInterface;

/**
 * This class creates ResponseValidator objects based on OpenAPI definitions.
 */
class ResponseValidatorBuilder extends AbstractValidatorBuilder implements ValidatorBuilderInterface
{
    /**
     * @var string
     */
    protected $adapter = HttpFoundationResponseAdapter::class;

    /**
     * Create a ResponseValidator object based on an OpenAPI definition.
     *
     * @param  string $method     The ValidatorBuilder object's method to use.
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    protected static function fromMethod(string $method, string $definition): ValidatorBuilderInterface
    {
        $builder = (new ValidatorBuilder())->$method($definition);

        return new ResponseValidatorBuilder($builder);
    }

    /**
     * Return the validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return new ResponseValidator($this->validatorBuilder->getResponseValidator(), new $this->adapter());
    }

    /**
     * Change the response adapter to use. The provided class must implement
     * \Osteel\OpenApi\Testing\Response\ResponseAdapterInterface.
     *
     * @param  string $class The adapter's class.
     * @return ValidatorBuilderInterface
     * @throws InvalidArgumentException
     */
    public function setAdapter(string $class): ValidatorBuilderInterface
    {
        if (! is_subclass_of($class, ResponseAdapterInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class %s does not implement the %s interface',
                $class,
                ResponseAdapterInterface::class
            ));
        }

        $this->adapter = $class;

        return $this;
    }
}
