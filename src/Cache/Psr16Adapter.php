<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Cache;

use InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\Psr16Adapter as CacheAdapter;

final class Psr16Adapter implements CacheAdapterInterface
{
    /**
     * Convert a PSR-16 caching library to a PSR-6 caching library.
     *
     * @param object $cache the caching library to convert
     */
    public function convert(object $cache): CacheItemPoolInterface
    {
        if ($cache instanceof CacheItemPoolInterface) {
            return $cache;
        }

        if ($cache instanceof CacheInterface) {
            return new CacheAdapter($cache);
        }

        throw new InvalidArgumentException(sprintf('Unsupported %s object received', $cache::class));
    }
}
