<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Adapters;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HttpFoundationAdapter implements MessageAdapterInterface
{
    /**
     * Convert a HttpFoundation object to a PSR-7 HTTP message.
     *
     * @param object $message the HTTP message to convert
     */
    public function convert(object $message): ResponseInterface|ServerRequestInterface
    {
        if ($message instanceof ResponseInterface || $message instanceof ServerRequestInterface) {
            return $message;
        }

        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        if ($message instanceof Response) {
            return $psrHttpFactory->createResponse($message);
        }

        if ($message instanceof Request) {
            return $psrHttpFactory->createRequest($message);
        }

        throw new InvalidArgumentException(sprintf('Unsupported %s object received', $message::class));
    }
}
