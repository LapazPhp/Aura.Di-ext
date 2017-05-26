<?php
namespace Lapaz\Aura\Di\Injection;

use Psr\Container\ContainerInterface;

class Locator
{
    /**
     * The service container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * The service name to retrieve.
     *
     * @var string
     */
    protected $service;

    /**
     * @param ContainerInterface $container The service container.
     * @param string $service The service to retrieve.
     */
    public function __construct(ContainerInterface $container, $service)
    {
        $this->container = $container;
        $this->service = $service;
    }

    /**
     * @return object The object located from service container.
     */
    public function __invoke()
    {
        return $this->container->get($this->service);
    }
}
