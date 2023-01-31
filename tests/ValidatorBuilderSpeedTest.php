<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use Osteel\OpenApi\Testing\Validator;
use Osteel\OpenApi\Testing\ValidatorBuilder;

class ValidatorBuilderSpeedTest extends TestCase
{
    public function definitionProvider(): array
    {
        return [
            // Uncomment these and comment *Raw versions for comparison.
            // ['fromYaml', file_get_contents(self::$yamlDefinition)],
            // ['fromJson', file_get_contents(self::$jsonDefinition)],
            ['fromYamlRaw', file_get_contents(self::$yamlDefinition)],
            ['fromJsonRaw', file_get_contents(self::$jsonDefinition)],
        ];
    }

    /**
     * @dataProvider definitionProvider
     */
    public function testItBuildsAValidator(string $method, string $definition)
    {
        $result = ValidatorBuilder::$method($definition)->getValidator();

        $this->assertInstanceOf(Validator::class, $result);
    }
}
