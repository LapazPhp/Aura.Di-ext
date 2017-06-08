<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Resolver\Resolver;
use Interop\Container\ContainerInterface;

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


    /**
     * Returns a new LazyRequire.
     *
     * @param string $file The file to require.
     * @param array $params Parameter variables passed to script file.
     * @return LazyRequire
     */
    public function newLazyRequire($file, $params = [])
    {
        return new LazyRequire($file, $params);
    }

    /**
     * Returns a new LazyInclude.
     *
     * @param string $file The file to include.
     * @param array $params Parameter variables passed to script file.
     * @return LazyInclude
     */
    public function newLazyInclude($file, $params = [])
    {
        return new LazyInclude($file, $params);
    }
}
