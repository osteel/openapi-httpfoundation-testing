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
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param  string $file The OpenAPI definition's file.
     * @return ValidatorBuilderInterface
     */
    public static function fromYamlFile(string $file): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param  string $file The OpenAPI definition's file.
     * @return ValidatorBuilderInterface
     */
    public static function fromJsonFile(string $file): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition as YAML text.
     * @return ValidatorBuilderInterface
     */
    public static function fromYamlString(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param  string $definition The OpenAPI definition as JSON text.
     * @return ValidatorBuilderInterface
     */
    public static function fromJsonString(string $definition): ValidatorBuilderInterface;

    /**
     * Return the validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface;
}
