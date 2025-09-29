<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

use cebe\openapi\Reader;
use cebe\openapi\SpecObjectInterface;

final class OpenApiSpecFactory implements OpenApiSpecFactoryInterface
{
    /** @inheritDoc */
    public function readFromJsonFile(string $fileName): SpecObjectInterface
    {
        return Reader::readFromJsonFile($fileName);
    }

    /** @inheritDoc */
    public function readFromYamlFile(string $fileName): SpecObjectInterface
    {
        return Reader::readFromYamlFile($fileName);
    }
}
