<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
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
}
