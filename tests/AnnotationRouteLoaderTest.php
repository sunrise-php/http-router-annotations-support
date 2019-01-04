<?php

namespace Sunrise\Http\Router\Tests;

use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\AnnotationRouteLoader;
use Sunrise\Http\Router\RouteCollectionInterface;

class AnnotationRouteLoaderTest extends TestCase
{
	public function testLoadAnnotationRouteAfterContainNonexistenceClass()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.after must contain only existing middleware.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteAfterContainNonexistenceClass');
	}

	public function testLoadAnnotationRouteAfterContainNotMiddleware()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.after must contain only existing middleware.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteAfterContainNotMiddleware');
	}

	public function testLoadAnnotationRouteAfterNotArray()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.after must be an array.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteAfterNotArray');
	}

	public function testLoadAnnotationRouteAfterNotStringable()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.after must contain only existing middleware.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteAfterNotStringable');
	}

	public function testLoadAnnotationRouteBeforeContainNonexistenceClass()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.before must contain only existing middleware.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteBeforeContainNonexistenceClass');
	}

	public function testLoadAnnotationRouteBeforeContainNotMiddleware()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.before must contain only existing middleware.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteBeforeContainNotMiddleware');
	}

	public function testLoadAnnotationRouteBeforeNotArray()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.before must be an array.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteBeforeNotArray');
	}

	public function testLoadAnnotationRouteBeforeNotStringable()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.before must contain only existing middleware.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteBeforeNotStringable');
	}

	public function testLoadAnnotationRouteIdEmpty()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.id must be not an empty string.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteIdEmpty');
	}

	public function testLoadAnnotationRouteIdMissing()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.id must be not an empty string.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteIdMissing');
	}

	public function testLoadAnnotationRouteIdNotString()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.id must be not an empty string.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteIdNotString');
	}

	public function testLoadAnnotationRouteMethodsEmpty()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.methods must be not an empty array.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteMethodsEmpty');
	}

	public function testLoadAnnotationRouteMethodsMissing()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.methods must be not an empty array.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteMethodsMissing');
	}

	public function testLoadAnnotationRouteMethodsNotArray()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.methods must be not an empty array.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteMethodsNotArray');
	}

	public function testLoadAnnotationRouteMethodsNotStringable()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.methods must contain only strings.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRouteMethodsNotStringable');
	}

	public function testLoadAnnotationRoutePathEmpty()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.path must be not an empty string.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRoutePathEmpty');
	}

	public function testLoadAnnotationRoutePathMissing()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.path must be not an empty string.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRoutePathMissing');
	}

	public function testLoadAnnotationRoutePathNotString()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.path must be not an empty string.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRoutePathNotString');
	}

	public function testLoadAnnotationRoutePathWithInvalidPatterns()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('@Route.path must contain only valid regular expressions.');

		(new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/InvalidController/AnnotationRoutePathWithInvalidPatterns');
	}

	public function testLoad()
	{
		$collection = (new AnnotationRouteLoader)->load(__DIR__ . '/App/Http/Controller');
		$this->assertInstanceOf(RouteCollectionInterface::class, $collection);

		$routes = [];
		foreach ($collection->getRoutes() as $route) {
			$routes[$route->getId()]['path'] = $route->getPath();
			$routes[$route->getId()]['methods'] = $route->getMethods();
			$routes[$route->getId()]['patterns'] = $route->getPatterns();
			$routes[$route->getId()]['middlewareStack'] = [];

			foreach ($route->getMiddlewareStack() as $middleware) {
				$routes[$route->getId()]['middlewareStack'][] = \get_class($middleware);
			}
		}

		$this->assertEquals([
			'foo' => [
				'path' => '/foo/{id}/{slug}',
				'methods' => ['GET', 'POST'],
				'patterns' => [
					'id' => '\d+',
					'slug' => '\w+',
				],
				'middlewareStack' => [
					'Sunrise\Http\Router\Tests\App\Http\Middleware\FooMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BarMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Controller\FooController',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BazMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\QuxMiddlewareTest',
				],
			],
			'bar' => [
				'path' => '/bar/{id}/{slug}',
				'methods' => ['GET', 'POST'],
				'patterns' => [
					'id' => '\d+',
					'slug' => '\w+',
				],
				'middlewareStack' => [
					'Sunrise\Http\Router\Tests\App\Http\Middleware\FooMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BarMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Controller\BarController',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BazMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\QuxMiddlewareTest',
				],
			],
			'quux' => [
				'path' => '/quux/{id}/{slug}',
				'methods' => ['GET', 'POST'],
				'patterns' => [
					'id' => '\d+',
					'slug' => '\w+',
				],
				'middlewareStack' => [
					'Sunrise\Http\Router\Tests\App\Http\Middleware\FooMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BarMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Controller\Baz\Qux\QuuxController',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BazMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\QuxMiddlewareTest',
				],
			],
			'quuux' => [
				'path' => '/quuux/{id}/{slug}',
				'methods' => ['GET', 'POST'],
				'patterns' => [
					'id' => '\d+',
					'slug' => '\w+',
				],
				'middlewareStack' => [
					'Sunrise\Http\Router\Tests\App\Http\Middleware\FooMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BarMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Controller\Baz\Qux\QuuuxController',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\BazMiddlewareTest',
					'Sunrise\Http\Router\Tests\App\Http\Middleware\QuxMiddlewareTest',
				],
			],
		], $routes);
	}
}
