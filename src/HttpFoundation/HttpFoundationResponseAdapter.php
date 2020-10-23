<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\HttpFoundation;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Osteel\OpenApi\Testing\ResponseAdapter;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response;

class HttpFoundationResponseAdapter implements ResponseAdapter
{
    /**
     * {@inheritDoc}
     *
     * @param  Response|ResponseInterface $response The response object to convert.
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function convert(object $response): ResponseInterface
    {
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        if ($response instanceof Response) {
            $psr17Factory   = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

            return $psrHttpFactory->createResponse($response);
        }

        throw new InvalidArgumentException(sprintf(
            'Can only validate responses of type %s or %s; %s received',
            Response::class,
            ResponseInterface::class,
            get_class($response)
        ));
    }
}
