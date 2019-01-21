# Annotations support for [Sunrise HTTP Router](https://github.com/sunrise-php/http-router)

[![Gitter](https://badges.gitter.im/sunrise-php/support.png)](https://gitter.im/sunrise-php/support)
[![Build Status](https://api.travis-ci.com/sunrise-php/http-router-annotations-support.svg?branch=master)](https://travis-ci.com/sunrise-php/http-router-annotations-support)
[![CodeFactor](https://www.codefactor.io/repository/github/sunrise-php/http-router-annotations-support/badge)](https://www.codefactor.io/repository/github/sunrise-php/http-router-annotations-support)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sunrise-php/http-router-annotations-support/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/http-router-annotations-support/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sunrise-php/http-router-annotations-support/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/http-router-annotations-support/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/sunrise/http-router-annotations-support/v/stable?format=flat)](https://packagist.org/packages/sunrise/http-router-annotations-support)
[![Total Downloads](https://poser.pugx.org/sunrise/http-router-annotations-support/downloads?format=flat)](https://packagist.org/packages/sunrise/http-router-annotations-support)
[![License](https://poser.pugx.org/sunrise/http-router-annotations-support/license?format=flat)](https://packagist.org/packages/sunrise/http-router-annotations-support)

## Installation (via composer)

```bash
composer require sunrise/http-router-annotations-support
```

## How to use?

#### QuickStart

```php
$router = new \Sunrise\Http\Router\Router();
$loader = new \Sunrise\Http\Router\AnnotationRouteLoader();

$routes = $loader->load(__DIR__ . '/src/Http/Controller/Foo');
$router->addRoutes($routes);

$routes = $loader->load(__DIR__ . '/src/Http/Controller/Bar');
$router->addRoutes($routes);

$response = $router->handle(...);
```

#### ExampleController

```php
declare(strict_types=1);
namespace App\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   id="resource.update",
 *   path="/resource/{id<\d+>}",
 *   methods={"PATCH"},
 *   before={
 *     "App\Http\Middleware\FooMiddleware",
 *     "App\Http\Middleware\BarMiddleware"
 *   },
 *   after={
 *     "App\Http\Middleware\BazMiddleware",
 *     "App\Http\Middleware\QuxMiddleware"
 *   }
 * )
 */
class ResourceUpdate implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        $response = $handler->handle($request);
        $resourceId = $request->getAttribute('id');

        // some code...

        return $response;
    }
}
```

## Test run

```bash
php vendor/bin/phpunit
```

## Api documentation

https://phpdoc.fenric.ru/
