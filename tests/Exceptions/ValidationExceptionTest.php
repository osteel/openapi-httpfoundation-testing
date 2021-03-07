<?php

namespace Osteel\OpenApi\Testing\Tests\Exceptions;

use Exception;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Osteel\OpenApi\Testing\Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function testItCreatesAnExceptionFromAValidationFailedException()
    {
        $exception = new ValidationFailed('foo', 0, new Exception('bar', 0, new Exception('baz')));
        $sut       = ValidationException::fromValidationFailed($exception);

        $this->assertEquals('foo: bar: baz', $sut->getMessage());
        $this->assertEquals($exception, $sut->getPrevious());
    }
}
