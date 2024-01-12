# OpenAPI HttpFoundation Testing

[![Build Status](https://github.com/osteel/php-cli-demo/workflows/CI/badge.svg)](https://github.com/osteel/php-cli-demo/actions)
[![Latest Stable Version](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/v)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![License](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/license)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![Downloads](http://poser.pugx.org/osteel/openapi-httpfoundation-testing/downloads)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)

Validate HttpFoundation requests and responses against OpenAPI (3+) definitions.

See [this post](https://tech.osteel.me/posts/openapi-backed-api-testing-in-php-projects-a-laravel-example "OpenAPI-backed API testing in PHP projects – a Laravel example") for more details and [this repository](https://github.com/osteel/openapi-httpfoundation-testing-laravel-example) for an example use in a Laravel project.

> 💡 While you can safely use this package for your projects, as long as version `1.0` has not been released "minor" version patches can contain breaking changes. Make sure to check the [release section](../../releases) before you upgrade.

## Why?

[OpenAPI](https://swagger.io/specification/) is a specification intended to describe RESTful APIs in a way that can be understood by both humans and machines.

By validating an API's requests and responses against the OpenAPI definition that describes it, we guarantee that the API is used correctly and behaves in accordance with the documentation we provide, thus making the OpenAPI definition the single source of truth.

The [HttpFoundation component](https://symfony.com/doc/current/components/http_foundation.html) is developed and maintained as part of the [Symfony framework](https://symfony.com/). It is used to handle HTTP requests and responses in projects such as Symfony, Laravel, Drupal, and [many others](https://symfony.com/components/HttpFoundation).

## How does it work?

This package is built on top of [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator), which validates [PSR-7 messages](https://www.php-fig.org/psr/psr-7/) against OpenAPI definitions.

It converts HttpFoundation request and response objects to PSR-7 messages using Symfony's [PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html) and [Tobias Nyholm](https://github.com/Nyholm)'s [PSR-7 implementation](https://github.com/Nyholm/psr7), before passing them on to OpenAPI PSR-7 Message Validator.

## Installation

> 💡 This package is mostly intended to be used as part of an API test suite.

Via Composer:

```bash
$ composer require --dev osteel/openapi-httpfoundation-testing
```

## Usage

Import the builder class:

```php
use Osteel\OpenApi\Testing\ValidatorBuilder;
```

Use the builder to create a [`\Osteel\OpenApi\Testing\Validator`](/src/Validator.php) object, using one of the available factory methods for YAML or JSON:

```php
// From a file:

$validator = ValidatorBuilder::fromYamlFile($yamlFile)->getValidator();
$validator = ValidatorBuilder::fromJsonFile($jsonFile)->getValidator();

// From a string:

$validator = ValidatorBuilder::fromYamlString($yamlString)->getValidator();
$validator = ValidatorBuilder::fromJsonString($jsonString)->getValidator();

// Automatic detection (slower):

$validator = ValidatorBuilder::fromYaml($yamlFileOrString)->getValidator();
$validator = ValidatorBuilder::fromJson($jsonFileOrString)->getValidator();
```

> 💡 You can also use a dependency injection container to bind the `ValidatorBuilder` class to the [`ValidatorBuilderInterface`](/src/ValidatorBuilderInterface.php) interface it implements and inject the interface instead, which would also be useful for testing and mocking.

You can now validate `\Symfony\Component\HttpFoundation\Request` and `\Symfony\Component\HttpFoundation\Response` objects for a given [path](https://swagger.io/specification/#paths-object) and method:

```php
$validator->validate($response, '/users', 'post');
```

> 💡 For convenience, objects implementing `\Psr\Http\Message\ServerRequestInterface` or `\Psr\Http\Message\ResponseInterface` are also accepted.

In the example above, we check that the response matches the OpenAPI definition for a `POST` request on the `/users` path.

Each of OpenAPI's [supported HTTP methods](https://swagger.io/docs/specification/paths-and-operations/ "Paths and Operations") (`DELETE`, `GET`, `HEAD`, `OPTIONS`, `PATCH`, `POST`, `PUT` and `TRACE`) also has a shortcut method that calls `validate` under the hood, meaning the line above could also be written this way:

```php
$validator->post($response, '/users');
```

Validating a request object works exactly the same way:

```php
$validator->post($request, '/users');
```

In the example above, we check that the request matches the OpenAPI definition for a `POST` request on the `/users` path.

The `validate` method returns `true` in case of success, and throw a [`\Osteel\OpenApi\Testing\Exceptions\ValidationException`](/src/Exceptions/ValidationException.php) exception in case of error.

## Caching

This package supports caching to speed up the parsing of OpenAPI definitions. Simply pass your [PSR-6](https://www.php-fig.org/psr/psr-6/) or [PSR-16](https://www.php-fig.org/psr/psr-16/) cache object to the `setCache` method of the [`ValidatorBuilder`](/src/ValidatorBuilder.php) class.

Here is an example using Symfony's [Array Cache Adapter](https://symfony.com/doc/current/components/cache/adapters/array_cache_adapter.html "Array Cache Adapter"):

```php
use Osteel\OpenApi\Testing\ValidatorBuilder;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

$cache = new ArrayAdapter();
$validator = ValidatorBuilder::fromYamlFile($yamlFile)->setCache($cache)->getValidator();
```

## Extending the package

There are two main extension points – message adapters and cache adapters.

### Message adapters

The [`ValidatorBuilder`](/src/ValidatorBuilder.php) class uses the [`HttpFoundationAdapter`](/src/Adapters/HttpFoundationAdapter.php) class as its default HTTP message adapter. This class converts HttpFoundation request and response objects to their PSR-7 counterparts.

If you need to change the adapter's logic, or if you need a new adapter altogether, create a class implementing the [`MessageAdapterInterface`](/src/Adapters/MessageAdapterInterface.php) interface and pass it to the `setMessageAdapter` method of the [`ValidatorBuilder`](/src/ValidatorBuilder.php) class:

```php
$validator = ValidatorBuilder::fromYamlFile($yamlFile)
    ->setMessageAdapter($yourAdapter)
    ->getValidator();
```

### Cache adapters

The [`ValidatorBuilder`](/src/ValidatorBuilder.php) class uses the [`Psr16Adapter`](/src/Cache/Psr16Adapter.php) class as its default cache adapter. This class converts PSR-16 cache objects to their PSR-6 counterparts.

If you need to change the adapter's logic, or if you need a new adapter altogether, create a class implementing the [`CacheAdapterInterface`](/src/Cache/CacheAdapterInterface.php) interface and pass it to the `setCacheAdapter` method of the [`ValidatorBuilder`](/src/ValidatorBuilder.php) class:

```php
$validator = ValidatorBuilder::fromYamlFile($yamlFile)
    ->setCacheAdapter($yourAdapter)
    ->getValidator();
```

### Other interfaces

The [`ValidatorBuilder`](/src/ValidatorBuilder.php) and [`Validator`](/src/Validator.php) classes are `final` but they implement the [`ValidatorBuilderInterface`](/src/ValidatorBuilderInterface.php) and [`ValidatorInterface`](/src/ValidatorInterface.php) interfaces respectively for which you can provide your own implementations if you need to.

## Change log

Please see the [Releases section](../../releases) for details.

## Contributing

Please see [CONTRIBUTING](/.github/CONTRIBUTING.md) for details.

## Credits

**People**

- [Yannick Chenot](https://github.com/osteel)
- [Patrick Rodacker](https://github.com/lordrhodos)
- [Johnathan Michael Dell](https://github.com/johnathanmdell)
- [Paul Mitchum](https://github.com/paul-m)
- [All Contributors](../../contributors)

Special thanks to [Pavel Batanov](https://github.com/scaytrase) for his advice on structuring the package.

**Packages**

- [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator)
- [The PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html)
- [PSR-7 implementation](https://github.com/Nyholm/psr7)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
