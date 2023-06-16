<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Adapters;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface AdapterInterface
{
    /**
     * Convert a HTTP message to a PSR-7 HTTP message.
     *
     * @param Request|Response|ResponseInterface|ServerRequestInterface $message the HTTP message to convert
     */
    public function convert(Request|Response|ResponseInterface|ServerRequestInterface $message): MessageInterface;
}
