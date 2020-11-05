# OpenAPI HttpFoundation Response Testing

[![Latest Stable Version](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/v)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![License](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/license)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![Build Status](https://travis-ci.com/osteel/openapi-httpfoundation-testing.svg?token=SDx8eeySnDpzswpLVTU3&branch=main)](https://travis-ci.com/osteel/openapi-httpfoundation-testing)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/osteel/openapi-httpfoundation-testing/badges/quality-score.png?b=main&s=bef9ddbf29dac69612a3092e4761e14ce768bccd)](https://scrutinizer-ci.com/g/osteel/openapi-httpfoundation-testing/?branch=main)

Strengthen your API tests by validating HttpFoundation responses against OpenAPI (3.0.x) definitions.

## Why?

[OpenAPI](https://swagger.io/specification/) is a specification intended to describe RESTful APIs in a way that is understood by humans and machines alike.

By validating an API's responses against the OpenAPI definition that describes it, we guarantee that the API's behaviour conforms to the documentation we provide, thus making the OpenAPI definition the single source of truth.

The [HttpFoundation component](https://symfony.com/doc/current/components/http_foundation.html) is developed and maintained as part of the [Symfony framework](https://symfony.com/). It is used to handle HTTP requests and responses in projects such as Symfony, Laravel, Drupal, and many other major industry players (see the [extended list](https://symfony.com/components/HttpFoundation)).

## How does it work?

This package is built upon the [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator) package, which validates [PSR-7 messages](https://www.php-fig.org/psr/psr-7/) against OpenAPI definitions.

It essentially converts HttpFoundation response objects to PSR-7 messages using Symfony's [PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html) and [Tobias Nyholm](https://github.com/Nyholm)'s [PSR-7 implementation](https://github.com/Nyholm/psr7), before passing them on to the OpenAPI PSR-7 Message Validator.

## Install

Via Composer:

```bash
$ composer require --dev osteel/openapi-httpfoundation-testing
```

> 💡 This package is meant to be used for development only, as part of your API test suite.

## Usage

First, import the builder in the class that will perform the validation:

```php
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;
```

Use the builder to create a `Osteel\OpenApi\Testing\ResponseValidator` object, feeding it a YAML or JSON OpenAPI definition:

```php
$validator = ResponseValidatorBuilder::fromYaml('my-definition.yaml')->getValidator();

// or

$validator = ResponseValidatorBuilder::fromJson('my-definition.json')->getValidator();
```

> 💡 Instead of a file, you can also pass a YAML or JSON string directly.

You can now validate a `Symfony\Component\HttpFoundation\Response` object for a given [path](https://swagger.io/specification/#paths-object) and method:

```php
$validator->validate('/users', 'post', $response);
```

> 💡 For convenience, responses implementing `Psr\Http\Message\ResponseInterface` are also accepted.

In the example above, we check that the response matches the OpenAPI definition for a `POST` request on the `/users` path.

Each of OpenAPI's supported HTTP methods (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `HEAD`, `OPTIONS` and `TRACE`) also has a shortcut method that calls `validate` under the hood, meaning the line above could also be written this way:

```php
$validator->post('/users', $response);
```

The `validate` method returns `true` in case of success, and throws [PSR-7 message-related exceptions](https://github.com/thephpleague/openapi-psr7-validator#exceptions) from the underlying OpenAPI PSR-7 Message Validator package in case of error.

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
