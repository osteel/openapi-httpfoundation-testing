<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use Osteel\OpenApi\Testing\ResponseValidator;
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;

class ResponseValidatorTest extends TestCase
{
    /**
     * @var ResponseValidator
     */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = ResponseValidatorBuilder::fromYaml(self::$yamlDefinition)->getValidator();
    }

    public function testItDoesNotValidateTheHttpFoundationResponse()
    {
        $this->expectException(\League\OpenAPIValidation\PSR7\Exception\ValidationFailed::class);

        $this->sut->validate('/test', 'get', $this->httpFoundationResponse(['baz' => 'bar']));
    }

    public function testItValidatesTheHttpFoundationResponse()
    {
        $result = $this->sut->validate('/test', 'get', $this->httpFoundationResponse(['foo' => 'bar']));

        $this->assertTrue($result);
    }

    public function testItDoesNotValidateThePsr7MessageResponse()
    {
        $this->expectException(\League\OpenAPIValidation\PSR7\Exception\ValidationFailed::class);

        $this->sut->validate('/test', 'get', $this->psr7Response(['baz' => 'bar']));
    }

    public function testItValidatesThePsr7MessageResponse()
    {
        $result = $this->sut->validate('/test', 'get', $this->psr7Response(['foo' => 'bar']));

        $this->assertTrue($result);
    }

    public function pathProvider(): array
    {
        return [
            ['/test'],
            ['test'],
        ];
    }

    /**
     * @dataProvider pathProvider
     */
    public function testItFixesThePath(string $path)
    {
        $result = $this->sut->validate($path, 'get', $this->httpFoundationResponse(['foo' => 'bar']));

        $this->assertTrue($result);
    }

    public function methodProvider(): array
    {
        return [
            ['post'],
            ['put'],
            ['patch'],
            ['delete'],
            ['head'],
            ['options'],
            ['trace'],
        ];
    }

    /**
     * @dataProvider methodProvider
     */
    public function testItValidatesTheResponseUsingMethodShortcuts(string $method)
    {
        $result = $this->sut->$method('/test', $this->httpFoundationResponse());

        $this->assertTrue($result);
    }
}
