<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Injection\LazyInterface;
use Interop\Container\ContainerInterface;

class LazyGetTest extends \PHPUnit_Framework_TestCase
{
    public function testLaziness()
    {
        $container = $this->createMock(ContainerInterface::class);
        $lazyObject = new LazyGet($container, 'foo');

        $this->assertInstanceOf(LazyInterface::class, $lazyObject);
    }
}
