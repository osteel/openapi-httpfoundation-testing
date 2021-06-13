<?php

namespace Osteel\OpenApi\Testing\Tests\Adapters;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\Adapters\HttpFoundationAdapter;
use Osteel\OpenApi\Testing\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpFoundationAdapterTest extends TestCase
{
    /**
     * @var HttpFoundationAdapter
     */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new HttpFoundationAdapter();
    }

    public function testItDoesNotConvertTheMessageBecauseTheTypeIsNotSupported()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported InvalidArgumentException object received');

        $this->sut->convert(new InvalidArgumentException());
    }

    public function testItConvertsTheHttpFoundationRequest()
    {
        $result = $this->sut->convert(Request::create('/foo'));

        $this->assertInstanceOf(ServerRequestInterface::class, $result);
    }

    public function testItLeavesTheRequestInterfaceUntouched()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $result  = $this->sut->convert($request);

        $this->assertEquals($request, $result);
    }

    public function testItConvertsAHttpFoundationResponse()
    {
        $result = $this->sut->convert(new Response());

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testItLeavesTheResponseInterfaceUntouched()
    {
        $response = $this->createMock(ResponseInterface::class);
        $result   = $this->sut->convert($response);

        $this->assertEquals($response, $result);
    }
}
