<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Osteel\OpenApi\Testing\HttpFoundation\HttpFoundationResponseAdapter;

/**
 * This class creates ResponseValidator objects based on OpenAPI definitions.
 */
class ResponseValidatorBuilder
{
    /**
     * @var ValidatorBuilder
     */
    protected $validatorBuilder;

    /**
     * @var string
     */
    protected $adapter = HttpFoundationResponseAdapter::class;

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
     * Create a ResponseValidator object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ResponseValidatorBuilder
     */
    public static function fromYaml(string $definition): ResponseValidatorBuilder
    {
        $method = is_file($definition) ? 'fromYamlFile' : 'fromYaml';

        return self::fromMethod($method, $definition);
    }

    /**
     * Create a ResponseValidator object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ResponseValidatorBuilder
     */
    public static function fromJson(string $definition): ResponseValidatorBuilder
    {
        $method = is_file($definition) ? 'fromJsonFile' : 'fromJson';

        return self::fromMethod($method, $definition);
    }

    /**
     * Create a ResponseValidator object based on an OpenAPI definition.
     *
     * @param  string $method     The ValidatorBuilder object's method to use.
     * @param  string $definition The OpenAPI definition.
     * @return ResponseValidatorBuilder
     */
    private static function fromMethod(string $method, string $definition): ResponseValidatorBuilder
    {
        $builder = (new ValidatorBuilder())->$method($definition);

        return new ResponseValidatorBuilder($builder);
    }

    /**
     * Return the ResponseValidator object.
     *
     * @return ResponseValidator
     */
    public function getValidator(): ResponseValidator
    {
        return new ResponseValidator($this->validatorBuilder->getResponseValidator(), new $this->adapter());
    }

    /**
     * Change the response adapter to be used. The provided class must
     * implement the \Osteel\OpenApi\Testing\ResponseAdapter interface.
     *
     * @param  string $class The adapter's class.
     * @return self
     * @throws InvalidArgumentException
     */
    public function setAdapter(string $class): self
    {
        if (! is_subclass_of($class, ResponseAdapter::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class %s does not implement the %s interface',
                $class,
                ResponseAdapter::class
            ));
        }

        $this->adapter = $class;

        return $this;
    }
}
