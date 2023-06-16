<?php

namespace Osteel\OpenApi\Testing\Tests\Adapters;

use Osteel\OpenApi\Testing\Adapters\HttpFoundationAdapter;
use Osteel\OpenApi\Testing\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpFoundationAdapterTest extends TestCase
{
    private HttpFoundationAdapter $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new HttpFoundationAdapter();
    }

    public function test_it_converts_the_http_foundation_request()
    {
        $result = $this->sut->convert(Request::create('/foo'));

        $this->assertInstanceOf(ServerRequestInterface::class, $result);
    }

    public function test_it_leaves_the_request_interface_untouched()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $result = $this->sut->convert($request);

        $this->assertEquals($request, $result);
    }

    public function test_it_converts_a_http_foundation_response()
    {
        $result = $this->sut->convert(new Response());

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function test_it_leaves_the_response_interface_untouched()
    {
        $response = $this->createMock(ResponseInterface::class);
        $result = $this->sut->convert($response);

        $this->assertEquals($response, $result);
    }
}
