<?php declare(strict_types=1);

namespace Sunrise\Http\Router\Tests\App\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   id="bar",
 *   path="/bar/{id<\d+>}/{slug<\w+>}",
 *   methods={"GET", "POST"},
 *   before={
 *     "Sunrise\Http\Router\Tests\App\Http\Middleware\FooMiddlewareTest",
 *     "Sunrise\Http\Router\Tests\App\Http\Middleware\BarMiddlewareTest"
 *   },
 *   after={
 *     "Sunrise\Http\Router\Tests\App\Http\Middleware\BazMiddlewareTest",
 *     "Sunrise\Http\Router\Tests\App\Http\Middleware\QuxMiddlewareTest"
 *   }
 * )
 */
class BarController implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		return $handler->handle($request);
	}
}
