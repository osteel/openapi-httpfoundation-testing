<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder as BaseValidatorBuilder;
use Osteel\OpenApi\Testing\Adapters\HttpFoundationAdapter;
use Osteel\OpenApi\Testing\Adapters\MessageAdapterInterface;
use Osteel\OpenApi\Testing\Cache\CacheAdapterInterface;
use Osteel\OpenApi\Testing\Cache\Psr16Adapter;

/**
 * This class creates Validator objects based on OpenAPI definitions.
 */
final class ValidatorBuilder implements ValidatorBuilderInterface
{
    /** @var class-string<MessageAdapterInterface> */
    private string $adapter = HttpFoundationAdapter::class;

    /** @var class-string<CacheAdapterInterface> */
    private string $cacheAdapter = Psr16Adapter::class;

    public function __construct(private BaseValidatorBuilder $validatorBuilder)
    {
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition
     */
    public static function fromYaml(string $definition): ValidatorBuilderInterface
    {
        return self::isUrl($definition) || is_file($definition)
            ? self::fromYamlFile($definition)
            : self::fromYamlString($definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition
     */
    public static function fromJson(string $definition): ValidatorBuilderInterface
    {
        return self::isUrl($definition) || is_file($definition)
            ? self::fromJsonFile($definition)
            : self::fromJsonString($definition);
    }

    private static function isUrl(string $value): bool
    {
        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);

        return in_array($scheme, ['http', 'https'], true);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition's file
     */
    public static function fromYamlFile(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('readFromYamlFile', $definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition's file
     */
    public static function fromJsonFile(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('readFromJsonFile', $definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition as YAML text
     */
    public static function fromYamlString(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('readFromYaml', $definition, resolveReferences: true);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition as JSON text
     */
    public static function fromJsonString(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('readFromJson', $definition, resolveReferences: true);
    }

    /**
     * Create a Validator object based on an OpenAPI definition.
     *
     * @param string $method            the ValidatorBuilder object's method to use
     * @param string $definition        the OpenAPI definition
     * @param bool   $resolveReferences whether to resolve references in the definition
     */
    private static function fromMethod(string $method, string $definition, bool $resolveReferences = false): ValidatorBuilderInterface
    {
        $specObject = Reader::{$method}($definition);

        $resolveReferences && $specObject->resolveReferences(new ReferenceContext($specObject, '/'));

        $builder = (new BaseValidatorBuilder())->fromSchema($specObject);

        return new ValidatorBuilder($builder);
    }

    /** @inheritDoc */
    public function setCache(object $cache): ValidatorBuilderInterface
    {
        $adapter = new $this->cacheAdapter();

        $this->validatorBuilder->setCache($adapter->convert($cache));

        return $this;
    }

    /** @inheritDoc */
    public function getValidator(): ValidatorInterface
    {
        return new Validator(
            $this->validatorBuilder->getRoutedRequestValidator(),
            $this->validatorBuilder->getResponseValidator(),
            new $this->adapter()
        );
    }

    /**
     * Change the adapter to use. The provided class must implement \Osteel\OpenApi\Testing\Adapters\AdapterInterface.
     *
     * @param string $class the adapter's class
     *
     * @throws InvalidArgumentException
     */
    public function setMessageAdapter(string $class): ValidatorBuilder
    {
        if (is_subclass_of($class, MessageAdapterInterface::class)) {
            $this->adapter = $class;

            return $this;
        }

        throw new InvalidArgumentException(
            sprintf('Class %s does not implement the %s interface', $class, MessageAdapterInterface::class),
        );
    }

    /**
     * Change the cache adapter to use. The provided class must implement \Osteel\OpenApi\Testing\Cache\AdapterInterface.
     *
     * @param string $class the cache adapter's class
     *
     * @throws InvalidArgumentException
     */
    public function setCacheAdapter(string $class): ValidatorBuilder
    {
        if (is_subclass_of($class, CacheAdapterInterface::class)) {
            $this->cacheAdapter = $class;

            return $this;
        }

        throw new InvalidArgumentException(
            sprintf('Class %s does not implement the %s interface', $class, CacheAdapterInterface::class),
        );
    }
}
