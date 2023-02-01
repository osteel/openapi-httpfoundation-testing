<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\Adapters\AdapterInterface;
use Osteel\OpenApi\Testing\Tests\TestCase;
use Osteel\OpenApi\Testing\Validator;
use Osteel\OpenApi\Testing\ValidatorBuilder;

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

    /**
     * @dataProvider definitionProvider
     */
    public function testItBuildsAValidator(string $method, string $definition)
    {
        $result = ValidatorBuilder::$method($definition)->getValidator();

        $this->assertInstanceOf(Validator::class, $result);

        $request  = $this->httpFoundationRequest(static::PATH, 'get', ['foo' => 'bar']);
        $response = $this->httpFoundationResponse(['foo' => 'bar']);

        // Validate a request and a response to make sure the definition was correctly parsed.
        $this->assertTrue($result->get($request, static::PATH));
        $this->assertTrue($result->get($response, static::PATH));
    }

    public function testItDoesNotSetTheAdapterBecauseItsTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Class %s does not implement the %s interface',
            InvalidArgumentException::class,
            AdapterInterface::class
        ));

        ValidatorBuilder::fromYaml(self::$yamlDefinition)->setAdapter(InvalidArgumentException::class);
    }

    public function testItSetsTheAdapter()
    {
        ValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setAdapter(get_class($this->createMock(AdapterInterface::class)));

        // No exception means the test was successful.
        $this->assertTrue(true);
    }
}
