<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Adapters;

use Psr\Http\Message\MessageInterface;

interface AdapterInterface
{
    /**
     * Convert a HTTP message to a PSR-7 HTTP message.
     *
     * @param  object $message The HTTP message to convert.
     * @return MessageInterface
     */
    public function convert(object $message): MessageInterface;
}
