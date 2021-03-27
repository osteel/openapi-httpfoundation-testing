# OpenAPI HttpFoundation Testing

[![Latest Stable Version](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/v)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![License](https://poser.pugx.org/osteel/openapi-httpfoundation-testing/license)](//packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![Build Status](https://travis-ci.com/osteel/openapi-httpfoundation-testing.svg?token=SDx8eeySnDpzswpLVTU3&branch=main)](https://travis-ci.com/osteel/openapi-httpfoundation-testing)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/osteel/openapi-httpfoundation-testing/badges/quality-score.png?b=main&s=bef9ddbf29dac69612a3092e4761e14ce768bccd)](https://scrutinizer-ci.com/g/osteel/openapi-httpfoundation-testing/?branch=main)

Validate HttpFoundation requests and responses against OpenAPI (3.0.x) definitions.

See [this article](https://tech.osteel.me/posts/openapi-backed-api-testing-in-php-projects-a-laravel-example "OpenAPI-backed API testing in PHP projects – a Laravel example") for more details, and [this repository](https://github.com/osteel/openapi-httpfoundation-testing-laravel-example) for an example use in a Laravel project.

## Why?

[OpenAPI](https://swagger.io/specification/) is a specification intended to describe RESTful APIs in a way that is understood by humans and machines alike.

By validating an API's requests and responses against the OpenAPI definition that describes it, we guarantee that the API is used correctly and behaves in accordance with the documentation we provide, thus making the OpenAPI definition the single source of truth.

The [HttpFoundation component](https://symfony.com/doc/current/components/http_foundation.html) is developed and maintained as part of the [Symfony framework](https://symfony.com/). It is used to handle HTTP requests and responses in projects such as Symfony, Laravel, Drupal, and [many other major industry players](https://symfony.com/components/HttpFoundation).

## How does it work?

This package is built upon [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator), which validates [PSR-7 messages](https://www.php-fig.org/psr/psr-7/) against OpenAPI definitions.

It converts HttpFoundation request and response objects to PSR-7 messages using Symfony's [PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html) and [Tobias Nyholm](https://github.com/Nyholm)'s [PSR-7 implementation](https://github.com/Nyholm/psr7), before passing them on to OpenAPI PSR-7 Message Validator.

## Install

Via Composer:

```bash
$ composer require --dev osteel/openapi-httpfoundation-testing
```

💡 _This package is mostly intended to be used as part of an API test suite._

## Usage

### Response Validation
First, import the builder in the class that will perform the validation:

```php
use Osteel\OpenApi\Testing\Response\ResponseValidatorBuilder;
```

Use the builder to create a `\Osteel\OpenApi\Testing\Response\ResponseValidator` object, feeding it a YAML or JSON OpenAPI definition:

```php
$validator = ResponseValidatorBuilder::fromYaml('my-definition.yaml')->getValidator();

// or

$validator = ResponseValidatorBuilder::fromJson('my-definition.json')->getValidator();
```

💡 _Instead of a file, you can also pass a YAML or JSON string directly._

You can now validate a `\Symfony\Component\HttpFoundation\Response` object for a given [path](https://swagger.io/specification/#paths-object) and method:

```php
$validator->validate($response, '/users', 'post');
```

💡 _For convenience, responses implementing `\Psr\Http\Message\ResponseInterface` are also accepted._

In the example above, we check that the response matches the OpenAPI definition for a `POST` request on the `/users` path.

Each of OpenAPI's supported HTTP methods (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `HEAD`, `OPTIONS` and `TRACE`) also has a shortcut method that calls `validate` under the hood, meaning the line above could also be written this way:

```php
$validator->post($response, '/users');
```

The `validate` method returns `true` in case of success, and throws `\Osteel\OpenApi\Testing\Exceptions\ValidationException` exceptions in case of error.

### Request Validation

The procedure is the same as described in the [Response Validation](https://github.com/osteel/openapi-httpfoundation-testing#response-validation) section. Import the builder first:

```php
use Osteel\OpenApi\Testing\Request\RequestValidatorBuilder;
```

and use it to create a `\Osteel\OpenApi\Testing\Request\RequestValidator` object, feeding it a YAML or JSON OpenAPI definition:

```php
$validator = RequestValidatorBuilder::fromYaml('my-definition.yaml')->getValidator();

// or

$validator = RequestValidatorBuilder::fromJson('my-definition.json')->getValidator();
```

💡 _Instead of a file, you can also pass a YAML or JSON string directly._

You can now validate a `\Symfony\Component\HttpFoundation\Request` object:

```php
$validator->validate($request);
```

💡 _For convenience, requests implementing `\Psr\Http\Message\ServerRequestInterface` are also accepted._

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
