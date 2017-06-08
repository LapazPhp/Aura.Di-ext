# Aura.Di Vocabulary Extension

[![Build Status](https://travis-ci.org/LapazPhp/Aura.Di-ext.svg?branch=master)](https://travis-ci.org/LapazPhp/Aura.Di-ext)

- Optionally `->modifiedBy()` and `->modifiedByScript()` enabled after `$di->lazyNew()` and `$di->newFactory()`.
- New method `->newLocator()` to create pure callable object that returns the service.
- Optional parameter `$params = []` added to `->lazyRequire()` and `lazyInclude()`.

(`newLocator()` is simply non lazy version of `lazyGet()`.)

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
    'setLoggerFactory' => $dix->newLocator('logger'),
])->modifiedBy(function ($routerContainer) {
    $map = $routerContainer->getMap();
    $map->get('index', '/');
    // ...
));
```

### Require/Include

```php
$dix = ContainerExtension::createFrom($di);

$di->params[\Aura\Dispatcher\Dispatcher::class]['objects'] = $dix->lazyRequire(__DIR__ . '/objects.php', [
    'di' => $di,
    // 'anotherConfig' => ...
]);
```

You can use `$di` in `objects.php` to return configured lazy instances.
