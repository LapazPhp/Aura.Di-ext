# Aura.Di Vocabulary Extension

- Optionally `->modifiedBy()` and `->modifiedByScript()` enabled after `$di->lazyNew()` and `$di->newFactory()`.
- New method `$di->newLocator()` to create pure callable object that returns the service.

(`newLocator()` is non lazy version of `lazyGet()`.)

Unlike `ContainerConfig::modify()`, every modification is called on demand at the 1st time of `->get()`.

### Before

```php
$di->set('routerContainer', $di->lazy(function () use ($di) {
    $routerContainer = $di->newInstance(\Aura\Router\RouterContainer::class, [], [
        'setLoggerFactory' => function () use ($di) {
            return $di->get('logger');
        },
        // Don't use ->lazyGet() because the returned lazy object would be evaluated before injection.
    ]);

    $map = $routerContainer->getMap();
    $map->get('index', '/');
    // ...

    return $routerContainer;
));
```

### After

```php
$dix = ContainerExtension::createFrom($di);

$di->set('routerContainer', $dix->lazyNew(\Aura\Router\RouterContainer::class, [], [
    'setLoggerFactory' => $dix->newLocator('logger),
])->modifiedBy(function ($routerContainer) {
    $map = $routerContainer->getMap();
    $map->get('index', '/');
    // ...
));
```
