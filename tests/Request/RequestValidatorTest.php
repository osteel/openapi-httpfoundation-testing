<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests\Request;

use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Osteel\OpenApi\Testing\Request\RequestValidator;
use Osteel\OpenApi\Testing\Request\RequestValidatorBuilder;
use Osteel\OpenApi\Testing\Tests\TestCase;

class RequestValidatorTest extends TestCase
{
    /**
     * @var RequestValidator
     */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = RequestValidatorBuilder::fromYaml(self::$yamlDefinition)->getValidator();
    }

    public function bodylessMethodProvider(): array
    {
        return [
            ['get'],
            ['delete'],
            ['head'],
            ['options'],
            ['trace'],
        ];
    }

    /**
     * @dataProvider bodylessMethodProvider
     */
    public function testItValidatesHttpFoundationRequestWithoutPayload(string $method)
    {
        $result = $this->sut->validate($this->httpFoundationRequest('/test', $method));

        $this->assertTrue($result);
    }

    public function methodProvider(): array
    {
        return [
            ['post'],
            ['put'],
            ['patch'],
        ];
    }

    /**
     * @dataProvider methodProvider
     */
    public function testItDoesNotValidateTheHttpFoundationRequest(string $method)
    {
        $this->expectException(ValidationException::class);

        $this->sut->validate($this->httpFoundationRequest('/test', $method, ['baz' => 'bar']));
    }

    /**
     * @dataProvider methodProvider
     */
    public function testItValidatesTheHttpFoundationRequest(string $method)
    {
        $result = $this->sut->validate($this->httpFoundationRequest('/test', $method, ['foo' => 'bar']));

        $this->assertTrue($result);
    }

    /**
     * @dataProvider bodylessMethodProvider
     */
    public function testItDoesValidateThePsr7ServerRequestWithoutRequestBody(string $method)
    {
        $result = $this->sut->validate($this->psr7ServerRequest('/test', $method));

        $this->assertTrue($result);
    }

    /**
     * @dataProvider methodProvider
     */
    public function testItDoesNotValidateThePsr7ServerRequest(string $method)
    {
        $this->expectException(ValidationException::class);

        $this->sut->validate($this->psr7ServerRequest('/test', $method, ['baz' => 'bar']));
    }

    /**
     * @dataProvider methodProvider
     */
    public function testItValidatesThePsr7ServerRequest(string $method)
    {
        $result = $this->sut->validate($this->psr7ServerRequest('/test', $method, ['foo' => 'bar']));

        $this->assertTrue($result);
    }
}
