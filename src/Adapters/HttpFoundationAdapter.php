<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Adapters;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HttpFoundationAdapter implements AdapterInterface
{
    /**
     * @inheritDoc
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to convert
     */
    public function convert(Request|Response|ResponseInterface|ServerRequestInterface $message): MessageInterface
    {
        if ($message instanceof ResponseInterface || $message instanceof ServerRequestInterface) {
            return $message;
        }

        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        if ($message instanceof Response) {
            return $psrHttpFactory->createResponse($message);
        }

        return $psrHttpFactory->createRequest($message);
    }
}
