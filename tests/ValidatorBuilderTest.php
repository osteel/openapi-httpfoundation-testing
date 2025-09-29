<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use cebe\openapi\Reader;
use InvalidArgumentException;
use Osteel\OpenApi\Testing\Adapters\MessageAdapterInterface;
use Osteel\OpenApi\Testing\Cache\CacheAdapterInterface;
use Osteel\OpenApi\Testing\OpenApiSpecFactoryInterface;
use Osteel\OpenApi\Testing\Validator;
use Osteel\OpenApi\Testing\ValidatorBuilder;
use stdClass;

class ValidatorBuilderTest extends TestCase
{
    public function definitionProvider(): array
    {
        return [
            ['fromYaml', self::$yamlDefinition],
            ['fromYaml', file_get_contents(self::$yamlDefinition)],
            ['fromYamlFile', self::$yamlDefinition],
            ['fromYamlString', file_get_contents(self::$yamlDefinition)],
            ['fromJson', self::$jsonDefinition],
            ['fromJson', file_get_contents(self::$jsonDefinition)],
            ['fromJsonFile', self::$jsonDefinition],
            ['fromJsonString', file_get_contents(self::$jsonDefinition)],
        ];
    }

    /** @dataProvider definitionProvider */
    public function test_it_builds_a_validator(string $method, string $definition)
    {
        $result = ValidatorBuilder::$method($definition)->getValidator();

        $this->assertInstanceOf(Validator::class, $result);

        $request = $this->httpFoundationRequest(static::PATH, 'get', ['foo' => 'bar']);
        $response = $this->httpFoundationResponse(['foo' => 'bar']);

        // Validate a request and a response to make sure the definition was correctly parsed.
        $this->assertTrue($result->get($request, static::PATH));
        $this->assertTrue($result->get($response, static::PATH));
    }

    public function test_it_builds_a_validator_given_a_absolute_url(): void
    {
        $url = 'https://foobar.localhost/openapi.yaml';

        $openApiFactory = $this->createMock(OpenApiSpecFactoryInterface::class);
        $openApiFactory->expects($this->once())
            ->method('readFromYamlFile')
            ->with($url)
            ->willReturn(Reader::readFromYamlFile(self::$yamlDefinition));

        ValidatorBuilder::setOpenApiSpecFactoryResolver(fn () => $openApiFactory);

        $result = ValidatorBuilder::fromYaml($url)->getValidator();

        $this->assertInstanceOf(Validator::class, $result);

        $request = $this->httpFoundationRequest(static::PATH, 'get', ['foo' => 'bar']);
        $response = $this->httpFoundationResponse(['foo' => 'bar']);

        // Validate a request and a response to make sure the definition was correctly parsed.
        $this->assertTrue($result->get($request, static::PATH));
        $this->assertTrue($result->get($response, static::PATH));
    }

    public function test_it_does_not_set_the_adapter_because_its_type_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Class %s does not implement the %s interface',
            stdClass::class,
            MessageAdapterInterface::class
        ));

        ValidatorBuilder::fromYaml(self::$yamlDefinition)->setMessageAdapter(stdClass::class);
    }

    public function test_it_sets_the_adapter()
    {
        ValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setMessageAdapter($this->createMock(MessageAdapterInterface::class)::class);

        $this->addToAssertionCount(1);
    }

    public function test_it_does_not_set_the_cache_adapter_because_its_type_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Class %s does not implement the %s interface',
            stdClass::class,
            CacheAdapterInterface::class
        ));

        ValidatorBuilder::fromYaml(self::$yamlDefinition)->setCacheAdapter(stdClass::class);
    }

    public function test_it_sets_the_cache_adapter()
    {
        ValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setCacheAdapter($this->createMock(CacheAdapterInterface::class)::class);

        $this->addToAssertionCount(1);
    }
}
