<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use League\OpenAPIValidation\PSR7\ValidatorBuilder;

abstract class AbstractValidatorBuilder
{
    /**
     * @var ValidatorBuilder
     */
    protected $validatorBuilder;

    /**
     * @var string
     */
    protected $adapter;

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
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    public static function fromYaml(string $definition): ValidatorBuilderInterface
    {
        $method = is_file($definition) ? 'fromYamlFile' : 'fromYaml';

        return static::fromMethod($method, $definition);
    }

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    public static function fromJson(string $definition): ValidatorBuilderInterface
    {
        $method = is_file($definition) ? 'fromJsonFile' : 'fromJson';

        return static::fromMethod($method, $definition);
    }

    /**
     * Create a ValidatorBuilderInterface object based on an OpenAPI definition.
     *
     * @param  string $method     The ValidatorBuilder object's method to use.
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    abstract protected static function fromMethod(string $method, string $definition): ValidatorBuilderInterface;
}
