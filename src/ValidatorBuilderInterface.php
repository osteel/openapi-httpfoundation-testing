<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

interface ValidatorBuilderInterface
{
    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition or a file name.
     * @return ValidatorBuilderInterface
     */
    public static function fromYaml(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition or a file name.
     * @return ValidatorBuilderInterface
     */
    public static function fromJson(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition as YAML text.
     * @return ValidatorBuilderInterface
     */
    public static function fromYamlRaw(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition as JSON text.
     * @return ValidatorBuilderInterface
     */
    public static function fromJsonRaw(string $definition): ValidatorBuilderInterface;

    /**
     * Return the validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface;
}
