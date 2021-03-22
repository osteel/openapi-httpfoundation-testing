<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Osteel\OpenApi\Testing\HttpFoundation\HttpFoundationRequestAdapter;

/**
 * This class creates RequestValidator objects based on OpenAPI definitions.
 */
class RequestValidatorBuilder
{
    /**
     * @var ValidatorBuilder
     */
    protected $validatorBuilder;

    /**
     * @var string
     */
    protected $adapter = HttpFoundationRequestAdapter::class;

    /**
     * Constructor.
     *
     * @param  ValidatorBuilder $builder
     * @return void
     */
    public function __construct(ValidatorBuilder $builder)
    {
        $this->validatorBuilder = $builder;
    }

    /**
     * Create a RequestValidator object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return RequestValidatorBuilder
     */
    public static function fromYaml(string $definition): RequestValidatorBuilder
    {
        $method = is_file($definition) ? 'fromYamlFile' : 'fromYaml';

        return self::fromMethod($method, $definition);
    }

    /**
     * Create a RequestValidator object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return RequestValidatorBuilder
     */
    public static function fromJson(string $definition): RequestValidatorBuilder
    {
        $method = is_file($definition) ? 'fromJsonFile' : 'fromJson';

        return self::fromMethod($method, $definition);
    }

    /**
     * Create a RequestValidator object based on an OpenAPI definition.
     *
     * @param  string $method     The ValidatorBuilder object's method to use.
     * @param  string $definition The OpenAPI definition.
     * @return RequestValidatorBuilder
     */
    private static function fromMethod(string $method, string $definition): RequestValidatorBuilder
    {
        $builder = (new ValidatorBuilder())->$method($definition);

        return new RequestValidatorBuilder($builder);
    }

    /**
     * Return the RequestValidator object.
     *
     * @return RequestValidator
     */
    public function getValidator(): RequestValidator
    {
        return new RequestValidator($this->validatorBuilder->getRequestValidator(), new $this->adapter());
    }

    /**
     * Change the request adapter to be used. The provided class must
     * implement the \Osteel\OpenApi\Testing\RequestAdapter interface.
     *
     * @param  string $class The adapter's class.
     * @return self
     * @throws InvalidArgumentException
     */
    public function setAdapter(string $class): self
    {
        if (! is_subclass_of($class, RequestAdapter::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class %s does not implement the %s interface',
                $class,
                RequestAdapter::class
            ));
        }

        $this->adapter = $class;

        return $this;
    }
}
