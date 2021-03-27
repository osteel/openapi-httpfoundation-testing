<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\Response\Adapters\ResponseAdapterInterface;
use Osteel\OpenApi\Testing\Response\ResponseValidator;
use Osteel\OpenApi\Testing\Response\ResponseValidatorBuilder;
use Osteel\OpenApi\Testing\Tests\TestCase;

class ResponseValidatorBuilderTest extends TestCase
{
    public function definitionProvider(): array
    {
        return [
            ['fromYaml', self::$yamlDefinition],
            ['fromYaml', file_get_contents(self::$yamlDefinition)],
            ['fromJson', self::$jsonDefinition],
            ['fromJson', file_get_contents(self::$jsonDefinition)],
        ];
    }

    /**
     * @dataProvider definitionProvider
     */
    public function testItBuildsAResponseValidator(string $method, string $definition)
    {
        $result = ResponseValidatorBuilder::$method($definition)->getValidator();

        $this->assertInstanceOf(ResponseValidator::class, $result);

        // Validate a response to make sure the definition was correctly parsed.
        $this->assertTrue($result->get($this->httpFoundationResponse(['foo' => 'bar']), '/test'));
    }

    public function testItDoesNotSetTheAdapterBecauseItsTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Class %s does not implement the %s interface',
            InvalidArgumentException::class,
            ResponseAdapterInterface::class
        ));

        ResponseValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setAdapter(InvalidArgumentException::class);
    }

    public function testItSetsTheAdapter()
    {
        ResponseValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setAdapter(get_class($this->createMock(ResponseAdapterInterface::class)));

        // No exception means the test was successful.
        $this->assertTrue(true);
    }
}
