<?php

namespace Osteel\OpenApi\Testing\Tests\Cache;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\Cache\Psr16Adapter;
use Osteel\OpenApi\Testing\Tests\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use stdClass;

class Psr16AdapterTest extends TestCase
{
    private Psr16Adapter $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new Psr16Adapter();
    }

    public function test_it_does_not_convert_the_caching_library_because_the_type_is_not_supported()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported stdClass object received');

        $this->sut->convert(new stdClass());
    }

    public function test_it_converts_the_psr16_caching_library()
    {
        $result = $this->sut->convert($this->createMock(CacheInterface::class));

        $this->assertInstanceOf(CacheItemPoolInterface::class, $result);
    }

    public function test_it_leaves_the_psr6_caching_library_untouched()
    {
        $cache = $this->createMock(CacheItemPoolInterface::class);
        $result = $this->sut->convert($cache);

        $this->assertEquals($cache, $result);
    }
}
