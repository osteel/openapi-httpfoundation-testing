<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\SpecObjectInterface;
use Osteel\OpenApi\Testing\OpenApiSpecFactory;

class OpenApiSpecFactoryTest extends TestCase
{
    public function test_it_creates_an_open_api_spec_from_yaml_file(): void
    {
        $result = (new OpenApiSpecFactory())->readFromYamlFile(self::$yamlDefinition);

        $this->assertInstanceOf(SpecObjectInterface::class, $result);
        $this->assertInstanceOf(OpenApi::class, $result);
    }

    public function test_it_creates_an_open_api_spec_from_json_file(): void
    {
        $result = (new OpenApiSpecFactory())->readFromJsonFile(self::$jsonDefinition);

        $this->assertInstanceOf(SpecObjectInterface::class, $result);
        $this->assertInstanceOf(OpenApi::class, $result);
    }
}
