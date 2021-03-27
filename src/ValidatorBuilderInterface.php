<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

interface ValidatorBuilderInterface
{
    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    public static function fromYaml(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition.
     * @return ValidatorBuilderInterface
     */
    public static function fromJson(string $definition): ValidatorBuilderInterface;

    /**
     * Return the validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface;

    /**
     * Change the adapter to use.
     *
     * @param  string $class The adapter's class.
     * @return ValidatorBuilderInterface
     */
    public function setAdapter(string $class): ValidatorBuilderInterface;
}
