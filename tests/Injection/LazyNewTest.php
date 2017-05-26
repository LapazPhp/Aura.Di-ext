<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Injection\LazyInterface;
use Aura\Di\Resolver\Resolver;

class LazyNewTest extends \PHPUnit_Framework_TestCase
{
    public function testLaziness()
    {
        $resolver = $this->createMock(Resolver::class);
        $lazyObject = new LazyNew($resolver, \stdClass::class);

        $this->assertInstanceOf(LazyInterface::class, $lazyObject);
    }
}
