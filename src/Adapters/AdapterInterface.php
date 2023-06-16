<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Adapters;

use Psr\Http\Message\MessageInterface;

interface AdapterInterface
{
    /**
     * Convert a HTTP message to a PSR-7 HTTP message.
     *
     * @param object $message the HTTP message to convert
     */
    public function convert(object $message): MessageInterface;
}
