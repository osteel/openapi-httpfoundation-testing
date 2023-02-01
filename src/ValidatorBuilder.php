<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder as BaseValidatorBuilder;
use Osteel\OpenApi\Testing\Adapters\AdapterInterface;
use Osteel\OpenApi\Testing\Adapters\HttpFoundationAdapter;

/**
 * This class creates Validator objects based on OpenAPI definitions.
 */
final class ValidatorBuilder implements ValidatorBuilderInterface
{
    /**
     * @var string
     */
    private $adapter = HttpFoundationAdapter::class;

    /**
     * @var BaseValidatorBuilder
     */
    private $validatorBuilder;

    /**
     * Constructor.
     *
     * @param  BaseValidatorBuilder $builder
     * @return void
     */
    public function __construct(BaseValidatorBuilder $builder)
    {
        $this->validatorBuilder = $builder;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @param  string $definition The OpenAPI definition's file.
     * @return ValidatorBuilderInterface
     */
    public static function fromYamlFile(string $definition): ValidatorBuilderInterface
    {
        return static::fromMethod('fromYamlFile', $definition);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $definition The OpenAPI definition's file.
     * @return ValidatorBuilderInterface
     */
    public static function fromJsonFile(string $definition): ValidatorBuilderInterface
    {
        return static::fromMethod('fromJsonFile', $definition);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $definition The OpenAPI definition as YAML text.
     * @return ValidatorBuilderInterface
     */
    public static function fromYamlString(string $definition): ValidatorBuilderInterface
    {
        return static::fromMethod('fromYaml', $definition);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $definition The OpenAPI definition as JSON text.
     * @return ValidatorBuilderInterface
     */
    public static function fromJsonString(string $definition): ValidatorBuilderInterface
    {
        return static::fromMethod('fromJson', $definition);
    }

    /**
     * Create a Validator object based on an OpenAPI definition.
     *
     * @param  string $method     The ValidatorBuilder object's method to use.
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    private static function fromMethod(string $method, string $definition): ValidatorBuilderInterface
    {
        $builder = (new BaseValidatorBuilder())->$method($definition);

        return new ValidatorBuilder($builder);
    }

    /**
     * {@inheritdoc}
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return new Validator(
            $this->validatorBuilder->getRoutedRequestValidator(),
            $this->validatorBuilder->getResponseValidator(),
            new $this->adapter()
        );
    }

    /**
     * Change the adapter to use. The provided class must implement
     * \Osteel\OpenApi\Testing\Adapters\AdapterInterface.
     *
     * @param  string $class The adapter's class.
     * @return ValidatorBuilder
     * @throws InvalidArgumentException
     */
    public function setAdapter(string $class): ValidatorBuilder
    {
        if (! is_subclass_of($class, AdapterInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class %s does not implement the %s interface',
                $class,
                AdapterInterface::class
            ));
        }

        $this->adapter = $class;

        return $this;
    }
}
