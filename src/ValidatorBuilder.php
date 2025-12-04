<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder as BaseValidatorBuilder;
use Osteel\OpenApi\Testing\Adapters\HttpFoundationAdapter;
use Osteel\OpenApi\Testing\Adapters\MessageAdapterInterface;
use Osteel\OpenApi\Testing\Cache\CacheAdapterInterface;
use Osteel\OpenApi\Testing\Cache\Psr16Adapter;
use RuntimeException;

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
        return match (true) {
            self::isUrl($definition) => self::fromYamlUrl($definition),
            is_file($definition) => self::fromYamlFile($definition),
            default => self::fromYamlString($definition),
        };
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition
     */
    public static function fromJson(string $definition): ValidatorBuilderInterface
    {
        return match (true) {
            self::isUrl($definition) => self::fromJsonUrl($definition),
            is_file($definition) => self::fromJsonFile($definition),
            default => self::fromJsonString($definition),
        };
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
        return self::fromMethod('fromYamlFile', $definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition's file
     */
    public static function fromJsonFile(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('fromJsonFile', $definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition as YAML text
     */
    public static function fromYamlString(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('fromYaml', $definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition as JSON text
     */
    public static function fromJsonString(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('fromJson', $definition);
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition's URL
     *
     * @throws InvalidArgumentException if the URL is invalid
     * @throws RuntimeException         if the content of the URL cannot be read
     */
    public static function fromYamlUrl(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('fromYaml', self::getUrlContent($definition));
    }

    /**
     * @inheritDoc
     *
     * @param string $definition the OpenAPI definition's URL
     *
     * @throws InvalidArgumentException if the URL is invalid
     * @throws RuntimeException         if the content of the URL cannot be read
     */
    public static function fromJsonUrl(string $definition): ValidatorBuilderInterface
    {
        return self::fromMethod('fromJson', self::getUrlContent($definition));
    }

    /**
     * @throws InvalidArgumentException if the URL is invalid
     * @throws RuntimeException         if the content of the URL cannot be read
     */
    private static function getUrlContent(string $url): string
    {
        self::isUrl($url) || throw new InvalidArgumentException(sprintf('Invalid URL: %s', $url));

        if (($content = file_get_contents($url)) === false) {
            throw new RuntimeException(sprintf('Failed to read URL %s', $url));
        }

        return $content;
    }

    /**
     * Create a Validator object based on an OpenAPI definition.
     *
     * @param string $method     the ValidatorBuilder object's method to use
     * @param string $definition the OpenAPI definition
     */
    private static function fromMethod(string $method, string $definition): ValidatorBuilderInterface
    {
        $builder = (new BaseValidatorBuilder())->{$method}($definition);

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
