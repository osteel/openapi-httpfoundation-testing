<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\HttpFoundation;

use Osteel\OpenApi\Testing\ResponseValidator;
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;

/**
 * This trait provides an easy way to obtain and use a ResponseValidator object.
 */
trait ValidatesHttpFoundationResponses
{
    /**
     * Build and return a ResponseValidator object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ResponseValidator
     */
    protected function yamlValidator(string $definition): ResponseValidator
    {
        return $this->validator(ResponseValidatorBuilder::fromYaml($definition));
    }

    /**
     * Build and return a ResponseValidator object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ResponseValidator
     */
    protected function jsonValidator(string $definition): ResponseValidator
    {
        return $this->validator(ResponseValidatorBuilder::fromJson($definition));
    }

    /**
     * Build and return a ResponseValidator object based on an OpenAPI definition.
     *
     * @param  ResponseValidatorBuilder $builder
     * @return ResponseValidator
     */
    private function validator(ResponseValidatorBuilder $builder): ResponseValidator
    {
        return $builder->setAdapter(HttpFoundationResponseAdapter::class)->getValidator();
    }
}
