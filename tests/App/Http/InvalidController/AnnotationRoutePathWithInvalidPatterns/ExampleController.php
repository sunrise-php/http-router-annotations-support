<?php declare(strict_types=1);

namespace Sunrise\Http\Router\Tests\App\Http\InvalidController\AnnotationRoutePathWithInvalidPatterns;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   id="resource",
 *   path="/{id<#>}",
 *   methods={"GET"}
 * )
 */
class ExampleController implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		return $handler->handle($request);
	}
}
