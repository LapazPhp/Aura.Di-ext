<?php
namespace Lapaz\Aura\Di;

use Aura\Di\Container;
use Lapaz\Aura\Di\Injection\Factory;
use Lapaz\Aura\Di\Injection\InjectionFactoryExtension;
use Lapaz\Aura\Di\Injection\LazyGet;
use Lapaz\Aura\Di\Injection\LazyNew;
use Lapaz\Aura\Di\Injection\Locator;

/**
 * Aura.Di's Container extension.
 *
 * - [CHG] lazyNew() and newFactory() creates modification supported version factory.
 * - [ADD] newLocator() can creates a callable which returns the service by id. It's useful to avoid LazyInterface.
 *
 * ```
 * $lazyRouterContainer = $di->lazy(function () use ($di) {
 *     $routerContainer = $di->newInstance(\Aura\Router\RouterContainer::class, [], [
 *         'setLoggerFactory' => function () use ($di) {
 *             return $di->get('logger');
 *         },
 *         // Don't use ->lazyGet() because the returned lazy object would be evaluated before injection.
 *     ]);
 *     $map = $routerContainer->getMap();
 *     $map->get('index', '/');
 *     // ...
 *     return $routerContainer;
 * );
 * ```
 *
 * ```
 * $dix = ContainerExtension::createFrom($di);
 *
 * $lazyRouterContainer = $dix->lazyNew(\Aura\Router\RouterContainer::class, [], [
 *     'setLoggerFactory' => $dix->newLocator('logger),
 * ])->modifiedBy(function ($routerContainer) {
 *     $map = $routerContainer->getMap();
 *     $map->get('index', '/');
 *     // ...
 * );
 * ```
 *
 * @see \Aura\Di\Container
 */
class ContainerExtension
{
    /**
     * @var Container
     */
    protected $targetContainer;

    /**
     * @var InjectionFactoryExtension
     */
    protected $injectionFactoryEx;

    /**
     * ContainerExtension constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->targetContainer = $container;
        $this->injectionFactoryEx = new InjectionFactoryExtension($container->getInjectionFactory()->getResolver());
    }

    /**
     * @param Container $di
     * @return static
     */
    public static function createFrom(Container $di)
    {
        return new static($di);
    }

    /**
     * Returns a lazy object that creates a new instance (and modifies it).
     *
     * @param string $class The type of class of instantiate.
     * @param array $params Override parameters for the instance.
     * @param array $setters Override setters for the instance.
     * @return LazyNew
     */
    public function lazyNew($class, array $params = [], array $setters = [])
    {
        return $this->injectionFactoryEx->newLazyNew($class, $params, $setters);
    }

    /**
     * Returns a lazy object that gets a service.
     *
     * @param string $service The service name; it does not need to exist yet.
     * @return LazyGet
     */
    public function lazyGet($service)
    {
        return $this->injectionFactoryEx->newLazyGet($this->targetContainer, $service);
    }

    /**
     * Returns a factory that creates an object over and over again (as vs
     * creating it one time like the lazyNew() or newInstance() methods).
     *
     * @param string $class The factory will create an instance of this class.
     * @param array $params Override parameters for the instance.
     * @param array $setters Override setters for the instance.
     * @return Factory
     */
    public function newFactory($class, array $params = [], array $setters = [])
    {
        return $this->injectionFactoryEx->newFactory($class, $params, $setters);
    }

    /**
     * Returns a locator that gets a service.
     *
     * @param string $service The service name; it does not need to exist yet.
     * @return Locator
     */
    public function newLocator($service)
    {
        return $this->injectionFactoryEx->newLocator($this->targetContainer, $service);
    }
}
