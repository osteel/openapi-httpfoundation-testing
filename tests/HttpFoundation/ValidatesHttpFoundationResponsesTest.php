<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests\HttpFoundation;

use Osteel\OpenApi\Testing\HttpFoundation\ValidatesHttpFoundationResponses;
use Osteel\OpenApi\Testing\ResponseValidator;
use Osteel\OpenApi\Testing\Tests\TestCase;

class ValidatesHttpFoundationResponsesTest extends TestCase
{
    /**
     * @var object
     */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new class
        {
            use ValidatesHttpFoundationResponses;

            public function getYamlValidator(string $definition)
            {
                return $this->yamlValidator($definition);
            }

            public function getJsonValidator(string $definition)
            {
                return $this->jsonValidator($definition);
            }
        };
    }

    public function testItReturnsAYamlValidator()
    {
        $result = $this->sut->getYamlValidator(self::$yamlDefinition);

        $this->assertInstanceOf(ResponseValidator::class, $result);
    }

    public function testItReturnsAJsonValidator()
    {
        $result = $this->sut->getJsonValidator(self::$jsonDefinition);

        $this->assertInstanceOf(ResponseValidator::class, $result);
    }
}
