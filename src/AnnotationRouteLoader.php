<?php declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2018, Anatoly Fenric
 * @license https://github.com/sunrise-php/http-router-annotations-support/blob/master/LICENSE
 * @link https://github.com/sunrise-php/http-router-annotations-support
 */

namespace Sunrise\Http\Router;

/**
 * AnnotationRouteLoader
 */
class AnnotationRouteLoader extends AbstractAnnotationLoader
{

	/**
	 * @param string $destination
	 * @param callable $middlewareInitializer
	 *
	 * @return RouteCollectionInterface
	 */
	public function load(string $destination, callable $middlewareInitializer = null) : RouteCollectionInterface
	{
		$annotations = $this->findAnnotations($destination, 'Sunrise\Http\Router\Annotation\Route');
		$routeCollection = new RouteCollection();

		foreach ($annotations as $annotation) {
			$route = new Route(
				$annotation->id,
				$annotation->path,
				$annotation->methods
			);

			foreach ($annotation->patterns as $name => $value) {
				$route->addPattern($name, $value);
			}

			$middlewareStack = \array_merge(
				$annotation->before,
				[$annotation->source->getName()],
				$annotation->after
			);

			foreach ($middlewareStack as $middleware) {
				$route->addMiddleware(
					$middlewareInitializer ?
					$middlewareInitializer($middleware) :
					new $middleware
				);
			}

			$routeCollection->addRoute($route);
		}

		return $routeCollection;
	}
}
