<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Request\Adapters;

use Psr\Http\Message\ServerRequestInterface;

interface RequestAdapterInterface
{
    /**
     * Convert a request to a PSR-7 HTTP message.
     *
     * @param  object $request The request object to convert.
     * @return ServerRequestInterface
     */
    public function convert(object $request): ServerRequestInterface;
}
