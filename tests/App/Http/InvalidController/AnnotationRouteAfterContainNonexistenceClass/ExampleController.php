<?php declare(strict_types=1);

namespace Sunrise\Http\Router\Tests\App\Http\InvalidController\AnnotationRouteAfterContainNonexistenceClass;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   id="home",
 *   path="/",
 *   methods={"GET"},
 *   after={
 *     "Sunrise\Http\Router\Tests\App\Http\InvalidController\AnnotationRouteAfterContainNonexistenceClass\NonexistenceMiddleware"
 *   }
 * )
 */
class ExampleController implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		return $handler->handle($request);
	}
}
