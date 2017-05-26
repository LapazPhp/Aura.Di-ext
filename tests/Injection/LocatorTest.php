<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Exception\ServiceNotFound;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class LocatorTest extends TestCase
{
    public function testLocateSuccess()
    {
        $object = new \stdClass();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('object')->willReturn($object);

        $locator = new Locator($container, 'object');
        $this->assertSame($object, $locator());
    }

    /**
     * @expectedException \Aura\Di\Exception\ServiceNotFound
     */
    public function testLocateFail()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('object')->willThrowException(new ServiceNotFound());

        $locator = new Locator($container, 'object');
        $locator();
    }
}
