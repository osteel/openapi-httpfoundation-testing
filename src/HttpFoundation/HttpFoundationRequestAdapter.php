<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\HttpFoundation;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Osteel\OpenApi\Testing\RequestAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;

class HttpFoundationRequestAdapter implements RequestAdapter
{
    /**
     * {@inheritDoc}
     *
     * @param  Request|ServerRequestInterface $request The request object to convert.
     * @return ServerRequestInterface
     * @throws InvalidArgumentException
     */
    public function convert(object $request): ServerRequestInterface
    {
        if ($request instanceof ServerRequestInterface) {
            return $request;
        }

        if ($request instanceof Request) {
            $psr17Factory   = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

            return $psrHttpFactory->createRequest($request);
        }

        throw new InvalidArgumentException(sprintf(
            'Can only validate requests of type %s or %s; %s received',
            Request::class,
            ServerRequestInterface::class,
            get_class($request)
        ));
    }
}
