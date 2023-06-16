<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing;

interface ValidatorBuilderInterface
{
    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param string $definition the OpenAPI definition
     */
    public static function fromYaml(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param string $definition the OpenAPI definition
     */
    public static function fromJson(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param string $file the OpenAPI definition's file
     */
    public static function fromYamlFile(string $file): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param string $file the OpenAPI definition's file
     */
    public static function fromJsonFile(string $file): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a YAML OpenAPI definition.
     *
     * @param string $definition the OpenAPI definition as YAML text
     */
    public static function fromYamlString(string $definition): ValidatorBuilderInterface;

    /**
     * Create a ValidatorBuilderInterface object based on a JSON OpenAPI definition.
     *
     * @param string $definition the OpenAPI definition as JSON text
     */
    public static function fromJsonString(string $definition): ValidatorBuilderInterface;

    /** Return the validator. */
    public function getValidator(): ValidatorInterface;
}
