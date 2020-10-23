<?php

namespace Osteel\OpenApi\Testing\Tests\HttpFoundation;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\HttpFoundation\HttpFoundationResponseAdapter;
use Osteel\OpenApi\Testing\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class HttpFoundationResponseAdapterTest extends TestCase
{
    /**
     * @var HttpFoundationResponseAdapter
     */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new HttpFoundationResponseAdapter();
    }

    public function testItDoesNotConvertTheResponseBecauseItsTypeIsNotSupported()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Can only validate responses of type %s or %s; InvalidArgumentException received',
            Response::class,
            ResponseInterface::class
        ));

        $this->sut->convert(new InvalidArgumentException());
    }

    public function testItConvertsTheHttpFoundationResponse()
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
