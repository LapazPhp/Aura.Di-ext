<?php
namespace Lapaz\Aura\Di;

use Aura\Di\Container;
use Aura\Di\Injection\InjectionFactory;
use Aura\Di\Resolver\Resolver;
use Lapaz\Aura\Di\Injection\Factory;
use Lapaz\Aura\Di\Injection\LazyGet;
use Lapaz\Aura\Di\Injection\LazyNew;
use Lapaz\Aura\Di\Injection\Locator;
use PHPUnit\Framework\TestCase;

class ContainerExtensionTest extends TestCase
{
    protected $mockContainer;

    protected function setUp()
    {
        $mockResolver = $this->createMock(Resolver::class);

        $mockInjectionFactory = $this->getMockBuilder(InjectionFactory::class)
            ->enableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->setConstructorArgs([$mockResolver])
            ->getMock();

        $this->mockContainer = $this->getMockBuilder(Container::class)
            ->enableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->setConstructorArgs([$mockInjectionFactory])
            ->getMock();
    }


    public function testNewLazyGet()
    {
        $dix = ContainerExtension::createFrom($this->mockContainer);
        $lazyObject = $dix->lazyGet( 'foo');

        $this->assertInstanceOf(LazyGet::class, $lazyObject);
    }

    public function testNewLazyNew()
    {
        $dix = ContainerExtension::createFrom($this->mockContainer);
        $lazyObject = $dix->lazyNew(\stdClass::class);

        $this->assertInstanceOf(LazyNew::class, $lazyObject);
    }

    public function testNewLocator()
    {
        $dix = ContainerExtension::createFrom($this->mockContainer);
        $locator = $dix->newLocator( 'foo');

        $this->assertInstanceOf(Locator::class, $locator);
    }

    public function testNewFactory()
    {
        $dix = ContainerExtension::createFrom($this->mockContainer);
        $factory = $dix->newFactory(\stdClass::class);

        $this->assertInstanceOf(Factory::class, $factory);
    }


}
