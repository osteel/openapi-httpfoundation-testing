⚠️  **This package is under active development and not available for general use yet** ⚠️

# OpenAPI HttpFoundation Response Testing

[![Latest Version on Packagist](https://img.shields.io/packagist/v/osteel/openapi-httpfoundation-testing.svg?style=flat-square)](https://packagist.org/packages/osteel/openapi-httpfoundation-testing)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.com/osteel/openapi-httpfoundation-testing.svg?token=SDx8eeySnDpzswpLVTU3&branch=main)](https://travis-ci.com/osteel/openapi-httpfoundation-testing)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/osteel/openapi-httpfoundation-testing/badges/quality-score.png?b=main&s=bef9ddbf29dac69612a3092e4761e14ce768bccd)](https://scrutinizer-ci.com/g/osteel/openapi-httpfoundation-testing/?branch=main)

Increase your API's robustness by validating your HttpFoundation responses against OpenAPI 3.0 definitions in your integration tests.

## Why?

[OpenAPI](https://swagger.io/specification/) is a specification intended to describe RESTful APIs built with any language, for the API to be understood by humans and machines alike.

By validating HTTP responses against an OpenAPI definition, we ensure that the API's behaviour conforms to the documentation we provide (the OpenAPI definitions), thus making that documentation the API's single source of truth.

The [HttpFoundation component](https://symfony.com/doc/current/components/http_foundation.html) is developed and maintained as part of the [Symfony framework](https://symfony.com/), and is used to handle HTTP requests and responses in Symfony, Laravel, Drupal, PrestaShop, and other major industry players (see the [extended list](https://symfony.com/components/HttpFoundation)).

## How does it work?

This package is built upon the [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator) package, which validates [PSR-7 messages](https://www.php-fig.org/psr/psr-7/) against OpenAPI definitions.

It essentially converts HttpFoundation response objects to PSR-7 messages using Symfony's [PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html), before passing them on to the OpenAPI PSR-7 Message Validator. It also exposes an interface to make it easy to validate responses (see [Usage](#usage) below).

## Install

Via Composer:

``` bash
$ composer require --dev osteel/openapi-httpfoundation-testing
```

> 💡 This package is meant to be used for development only, as part of your test suite.

## Usage

### General

First, create a `Osteel\OpenApi\Testing\ResponseValidator` object using a YAML or JSON OpenAPI definition:

```php
$validator = new \Osteel\OpenApi\Testing\ResponseValidatorBuilder::fromYaml('my-definition.yaml')->getValidator();

// ... or...

$validator = new \Osteel\OpenApi\Testing\ResponseValidatorBuilder::fromJson('my-definition.json')->getValidator();
```

> 💡 Instead of a file, you can also pass a YAML or JSON string directly.

You can now validate your `Symfony\Component\HttpFoundation\Response` object for a given [path](https://swagger.io/specification/#paths-object) and method:

```php
$validator->validate('/users', 'post', $response);
```

> 💡 For convenience, responses implementing the `Psr\Http\Message\ResponseInterface` interface are also accepted.

In the example above, we check that the response matches the OpenAPI definition for a `POST` request on the `/users` path.

The `validate` method will return `true` in case of success, and throw an exception otherwise (see [below](#exceptions)).

There is an available shortcut for each of OpenAPI's supported HTTP methods (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `HEAD`, `OPTIONS` and `TRACE`), meaning the line above could also be written this way:

```php
$validator->post('/users', $response);
```

### Trait

In order to use the package in a less verbose way, the `ValidatesHttpFoundationResponses` trait can be imported in the class that will perform the validation:

```php
<?php

use Osteel\OpenApi\Testing\HttpFoundation\ValidatesHttpFoundationResponses;

class ExampleTest
{
    use ValidatesHttpFoundationResponses;

    public function testItValidatesTheResponse()
    {
        // Query the API to obtain a Symfony\Component\HttpFoundation\Response object.
        $response = $this->get('/api/users');

        // Make sure the response matches the OpenAPI definition
        $this->yamlValidator('my-definition.yaml')->get('/users', $response);

        // ... or...
        $this->jsonValidator('my-definition.json')->get('/users', $response);
    }
}
```

Both methods will return an instance of `Osteel\OpenApi\Testing\ResponseValidator` based on the provided definition; you can then use that object as described in the previous section.

### Exceptions

In case of error, the `validate` method will throw [PSR-7 message-related exceptions](https://github.com/thephpleague/openapi-psr7-validator#exceptions) from the underlying OpenAPI PSR-7 Message Validator package.

## Change log

Please see the [Releases section](../../releases) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@yannickchenot.com instead of using the issue tracker.

## Credits

- [Yannick Chenot](https://github.com/osteel)
- [All Contributors](../../contributors)
- [OpenAPI PSR-7 Message Validator](https://github.com/thephpleague/openapi-psr7-validator)
- [The PSR-7 Bridge](https://symfony.com/doc/current/components/psr7.html)

Special thanks to [Pavel Batanov](https://github.com/scaytrase) for his advice on structuring the package.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
