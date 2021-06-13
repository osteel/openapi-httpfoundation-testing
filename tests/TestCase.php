<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private const BASE_URI = 'http://localhost:8000/api';
    protected const PATH   = '/test';

    /**
     * @var string
     */
    protected static $yamlDefinition = __DIR__ . '/stubs/example.yaml';

    /**
     * @var string
     */
    protected static $jsonDefinition = __DIR__ . '/stubs/example.json';

    /**
     * Return a HttpFoundation response with the provided content.
     *
     * @param  array $content
     * @return Response
     */
    protected function httpFoundationResponse(array $content = null): Response
    {
        return new Response(
            $content ? json_encode($content) : '',
            $content ? Response::HTTP_OK : Response::HTTP_NO_CONTENT,
            $content ? ['Content-Type' => 'application/json'] : []
        );
    }

    /**
     * Return a PSR-7 response with the provided content.
     *
     * @param  array $content
     * @return ResponseInterface
     */
    protected function psr7Response(array $content = null): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);

        if (! $content) {
            $response->method('getStatusCode')->willReturn(Response::HTTP_NO_CONTENT);
            return $response;
        }

        $response->method('getBody')->willReturn(json_encode($content));
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('getHeader')->willReturn(['application/json']);

        return $response;
    }

    /**
     * Return a HttpFoundation request with the provided content.
     *
     * @param  string $uri
     * @param  string $method
     * @param  array $content
     * @return Request
     */
    protected function httpFoundationRequest(string $uri, string $method, array $content = null): Request
    {
        return Request::create(
            self::BASE_URI . $uri,
            $method,
            [],
            [],
            [],
            $content ? ['CONTENT_TYPE' => 'application/json'] : [],
            $content ? json_encode($content) : ''
        );
    }

    /**
     * Return a PSR-7 request with the provided content.
     *
     * @param  string $uri
     * @param  string $method
     * @param  array  $content
     * @return ServerRequestInterface
     */
    protected function psr7Request(
        string $uri,
        string $method,
        array $content = null
    ): ServerRequestInterface {
        $psr17Factory = new Psr17Factory();
        $uri          = $psr17Factory->createUri(self::BASE_URI . $uri);
        $stream       = $psr17Factory->createStream(json_encode($content));
        $request      = $psr17Factory->createServerRequest($method, $uri);

        if ($content) {
            $request = $request->withHeader('Content-Type', 'application/json');
        }

        return $request->withBody($stream);
    }
}
