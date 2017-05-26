<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Injection\LazyInterface;
use Aura\Di\Resolver\Resolver;
use PHPUnit\Framework\TestCase;

class LazyNewTest extends TestCase
{
    public function testLaziness()
    {
        $resolver = $this->createMock(Resolver::class);
        $lazyObject = new LazyNew($resolver, \stdClass::class);

        $this->assertInstanceOf(LazyInterface::class, $lazyObject);
    }
}
