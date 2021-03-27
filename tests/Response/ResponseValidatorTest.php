<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Osteel\OpenApi\Testing\Response\ResponseValidator;
use Osteel\OpenApi\Testing\Response\ResponseValidatorBuilder;

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
        $this->expectException(ValidationException::class);

        $this->sut->validate($this->httpFoundationResponse(['baz' => 'bar']), '/test', 'get');
    }

    public function testItValidatesTheHttpFoundationResponse()
    {
        $result = $this->sut->validate($this->httpFoundationResponse(['foo' => 'bar']), '/test', 'get');

        $this->assertTrue($result);
    }

    public function testItDoesNotValidateThePsr7MessageResponse()
    {
        $this->expectException(ValidationException::class);

        $this->sut->validate($this->psr7Response(['baz' => 'bar']), '/test', 'get');
    }

    public function testItValidatesThePsr7MessageResponse()
    {
        $result = $this->sut->validate($this->psr7Response(['foo' => 'bar']), '/test', 'get');

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
        $result = $this->sut->validate($this->httpFoundationResponse(['foo' => 'bar']), $path, 'get');

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
        $result = $this->sut->$method($this->httpFoundationResponse(), '/test');

        $this->assertTrue($result);
    }
}
