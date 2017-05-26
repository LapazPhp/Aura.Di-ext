<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Resolver\Resolver;
use Psr\Container\ContainerInterface;

/**
 * InjectionFactory extension
 * @see \Aura\Di\Injection\InjectionFactory
 */
class InjectionFactoryExtension
{
    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * InjectionFactoryExtension constructor.
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $class The class to create.
     * @param array $params Override params for the class.
     * @param array $setters Override setters for the class.
     * @return Factory
     */
    public function newFactory($class, array $params = [], array $setters = []) {
        return new Factory($this->resolver, $class, $params, $setters);
    }

    /**
     * @param ContainerInterface $container The service container.
     * @param string $service The service to retrieve.
     * @return Locator
     */
    public function newLocator(ContainerInterface $container, $service)
    {
        return new Locator($container, $service);
    }

    /**
     * @param string $class The type of class of instantiate.
     * @param array $params Override parameters for the instance.
     * @param array $setters Override setters for the instance.
     * @return LazyNew
     */
    public function newLazyNew($class, array $params = [], array $setters = []) {
        return new LazyNew($this->resolver, $class, $params, $setters);
    }

    /**
     * @param ContainerInterface $container The service container.
     * @param string $service The service to retrieve.
     * @return LazyGet
     */
    public function newLazyGet(ContainerInterface $container, $service)
    {
        return new LazyGet($container, $service);
    }
}
