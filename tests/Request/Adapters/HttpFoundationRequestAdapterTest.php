<?php

namespace Osteel\OpenApi\Testing\Tests\Request\Adapters;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\Request\Adapters\HttpFoundationRequestAdapter;
use Osteel\OpenApi\Testing\Tests\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class HttpFoundationRequestAdapterTest extends TestCase
{
    /**
     * @var HttpFoundationRequestAdapter
     */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new HttpFoundationRequestAdapter();
    }

    public function testItDoesNotConvertTheRequestBecauseItsTypeIsNotSupported()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Can only validate requests of type %s or %s; InvalidArgumentException received',
            Request::class,
            ServerRequestInterface::class
        ));

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
}
