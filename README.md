# OpenAPI HttpFoundation Testing

[![Build Status](https://github.com/osteel/php-cli-demo/workflows/CI/badge.svg)](https://github.com/osteel/php-cli-demo/actions)
[![Latest Stable Version](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/v)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![License](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/license)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![Downloads](http://poser.pugx.org/osteel/openapi-httpfoundation-testing/downloads)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)

Validate HttpFoundation requests and responses against OpenAPI (3.0.x) definitions.

See [this post](https://tech.osteel.me/posts/openapi-backed-api-testing-in-php-projects-a-laravel-example "OpenAPI-backed API testing in PHP projects – a Laravel example") for more details and [this repository](https://github.com/osteel/openapi-httpfoundation-testing-laravel-example) for an example use in a Laravel project.

💡 _While you can safely use this package for your projects, as long as version `1.0` has not been released "minor" version patches can contain breaking changes. Make sure to check the [release section](../../releases) before you upgrade._

## Why?

[OpenAPI](https://swagger.io/specification/) is a specification intended to describe RESTful APIs in a way that is understood by humans and machines alike.

By validating an API's requests and responses against the OpenAPI definition that describes it, we guarantee that the API is used correctly and behaves in accordance with the documentation we provide, thus making the OpenAPI definition the single source of truth.

The [HttpFoundation component](https://symfony.com/doc/current/components/http_foundation.html) is developed and maintained as part of the [Symfony framework](https://symfony.com/). It is used to handle HTTP requests and responses in projects such as Symfony, Laravel, Drupal, and [many others](https://symfony.com/components/HttpFoundation).

## How does it work?

This package is built upon the [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator) one, which validates [PSR-7 messages](https://www.php-fig.org/psr/psr-7/) against OpenAPI definitions.

It converts HttpFoundation request and response objects to PSR-7 messages using Symfony's [PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html) and [Tobias Nyholm](https://github.com/Nyholm)'s [PSR-7 implementation](https://github.com/Nyholm/psr7), before passing them on to OpenAPI PSR-7 Message Validator.

## Install

Via Composer:

```bash
$ composer require --dev osteel/openapi-httpfoundation-testing
```

💡 _This package is mostly intended to be used as part of an API test suite._

## Usage

Import the builder class:

```php
use Osteel\OpenApi\Testing\ValidatorBuilder;
```

Use the builder to create a `\Osteel\OpenApi\Testing\Validator` object, using one of the many factory methods for YAML or JSON:

```php
// Choose one:

$validator = ValidatorBuilder::fromYaml('my-definition.yaml')->getValidator();
$validator = ValidatorBuilder::fromJson('my-definition.json')->getValidator();

$validator = ValidatorBuilder::fromYamlFile('my-definition.yaml')->getValidator();
$validator = ValidatorBuilder::fromJsonFile('my-definition.json')->getValidator();

$validator = ValidatorBuilder::fromYamlString($already_loaded_yaml_string)->getValidator();
$validator = ValidatorBuilder::fromJsonString($already_loaded_json_string)->getValidator();
```

💡 _`ValidatorBuilder::fromYaml` and `ValidatorBuilder::fromJson` can take either a file name or the contents of the file._

You can now validate `\Symfony\Component\HttpFoundation\Request` and `\Symfony\Component\HttpFoundation\Response` objects for a given [path](https://swagger.io/specification/#paths-object) and method:

```php
$validator->validate($response, '/users', 'post');
```

💡 _For convenience, objects implementing `\Psr\Http\Message\ServerRequestInterface` or `\Psr\Http\Message\ResponseInterface` are also accepted._

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

The `validate` method returns `true` in case of success, and throws `\Osteel\OpenApi\Testing\Exceptions\ValidationException` exceptions in case of error.

## Change log

Please see the [Releases section](../../releases) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Credits

**People**

- [Yannick Chenot](https://github.com/osteel)
- [All Contributors](../../contributors)

Special thanks to [Pavel Batanov](https://github.com/scaytrase) for his advice on structuring the package.

**Packages**

- [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator)
- [The PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html)
- [PSR-7 implementation](https://github.com/Nyholm/psr7)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
