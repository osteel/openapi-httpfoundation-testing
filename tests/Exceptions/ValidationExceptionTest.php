<?php

namespace Osteel\OpenApi\Testing\Tests\Exceptions;

use Exception;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Osteel\OpenApi\Testing\Exceptions\ValidationException;
use Osteel\OpenApi\Testing\Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function testItCreatesAnExceptionFromAValidationFailedException()
    {
        $breadCrumb = new BreadCrumb('qux');
        $previous   = (new SchemaMismatch('baz'))->withBreadCrumb($breadCrumb);
        $exception  = new ValidationFailed('foo', 0, new Exception('bar', 0, $previous));
        $sut        = ValidationException::fromValidationFailed($exception);

        $this->assertEquals('foo: bar: baz Field: qux', $sut->getMessage());
        $this->assertEquals($exception, $sut->getPrevious());
    }
}
