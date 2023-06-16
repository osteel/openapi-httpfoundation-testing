<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Cache;

use Psr\Cache\CacheItemPoolInterface;

interface CacheAdapterInterface
{
    /**
     * Convert a caching library to a PSR-6 caching library.
     *
     * @param object $cache the caching library to convert
     */
    public function convert(object $cache): CacheItemPoolInterface;
}
