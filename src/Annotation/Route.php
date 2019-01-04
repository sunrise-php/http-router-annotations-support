<?php declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2018, Anatoly Fenric
 * @license https://github.com/sunrise-php/http-router-annotations-support/blob/master/LICENSE
 * @link https://github.com/sunrise-php/http-router-annotations-support
 */

namespace Sunrise\Http\Router\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Route
{

	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $path;

	/**
	 * @var array
	 */
	public $methods;

	/**
	 * @var array
	 */
	public $patterns = [];

	/**
	 * @var array
	 */
	public $before = [];

	/**
	 * @var array
	 */
	public $after = [];

	/**
	 * @param array $values
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(array $values)
	{
		if (empty($values['id']) || ! \is_string($values['id'])) {
			throw new \InvalidArgumentException('@Route.id must be not an empty string.');
		}
		if (empty($values['path']) || ! \is_string($values['path'])) {
			throw new \InvalidArgumentException('@Route.path must be not an empty string.');
		}
		if (empty($values['methods']) || ! \is_array($values['methods'])) {
			throw new \InvalidArgumentException('@Route.methods must be not an empty array.');
		}
		if (isset($values['before']) && ! \is_array($values['before'])) {
			throw new \InvalidArgumentException('@Route.before must be an array.');
		}
		if (isset($values['after']) && ! \is_array($values['after'])) {
			throw new \InvalidArgumentException('@Route.after must be an array.');
		}

		foreach ($values['methods'] as $value) {
			if (! \is_string($value)) {
				throw new \InvalidArgumentException('@Route.methods must contain only strings.');
			}
		}

		if (isset($values['before'])) {
			foreach ($values['before'] as $value) {
				if (! \is_string($value) || ! \class_exists($value) || ! \is_subclass_of($value, 'Psr\Http\Server\MiddlewareInterface')) {
					throw new \InvalidArgumentException('@Route.before must contain only existing middleware.');
				}
			}
		}

		if (isset($values['after'])) {
			foreach ($values['after'] as $value) {
				if (! \is_string($value) || ! \class_exists($value) || ! \is_subclass_of($value, 'Psr\Http\Server\MiddlewareInterface')) {
					throw new \InvalidArgumentException('@Route.after must contain only existing middleware.');
				}
			}
		}

		$this->path = \preg_replace_callback('/{(\w+)<([^<>]+)>}/', function($matches) {
			$this->patterns[$matches[1]] = $matches[2];
			return '{' . $matches[1] . '}';
		}, $values['path']);

		foreach ($this->patterns as $value) {
			if (false === @ \preg_match('#' . $value . '#', '')) {
				throw new \InvalidArgumentException('@Route.path must contain only valid regular expressions.');
			}
		}

		$this->id = $values['id'];
		$this->methods = $values['methods'];

		if (isset($values['before'])) {
			$this->before = $values['before'];
		}

		if (isset($values['after'])) {
			$this->after = $values['after'];
		}
	}
}
