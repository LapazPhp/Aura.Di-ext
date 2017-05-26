<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Resolver\Resolver;
use Interop\Container\ContainerInterface;

class InjectionFactoryExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Resolver
     */
    protected $mockResolver;

    protected function setUp()
    {
        $this->mockResolver = $this->createMock(Resolver::class);
    }

    public function testNewLazyGet()
    {
        $container = $this->createMock(ContainerInterface::class);

        $injectionFactoryEx = new InjectionFactoryExtension($this->mockResolver);
        $lazyObject = $injectionFactoryEx->newLazyGet($container, 'foo');

        $this->assertInstanceOf(LazyGet::class, $lazyObject);
    }

    public function testNewLazyNew()
    {
        $injectionFactoryEx = new InjectionFactoryExtension($this->mockResolver);
        $lazyObject = $injectionFactoryEx->newLazyNew(\stdClass::class);

        $this->assertInstanceOf(LazyNew::class, $lazyObject);
    }

    public function testNewLocator()
    {
        $container = $this->createMock(ContainerInterface::class);

        $injectionFactoryEx = new InjectionFactoryExtension($this->mockResolver);
        $locator = $injectionFactoryEx->newLocator($container, 'foo');

        $this->assertInstanceOf(Locator::class, $locator);
    }

    public function testNewFactory()
    {
        $injectionFactoryEx = new InjectionFactoryExtension($this->mockResolver);
        $factory = $injectionFactoryEx->newFactory(\stdClass::class);

        $this->assertInstanceOf(Factory::class, $factory);
    }
}
