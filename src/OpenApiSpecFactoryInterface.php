<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\SpecObjectInterface;

interface OpenApiSpecFactoryInterface
{
    /** @return SpecObjectInterface|OpenApi */
    public function readFromJsonFile(string $fileName): SpecObjectInterface;

    /** @return SpecObjectInterface|OpenApi */
    public function readFromYamlFile(string $fileName): SpecObjectInterface;
}
