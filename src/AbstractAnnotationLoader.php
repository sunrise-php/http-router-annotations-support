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
 * Import classes
 */
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

/**
 * AbstractAnnotationLoader
 */
abstract class AbstractAnnotationLoader
{

	/**
	 * @var SimpleAnnotationReader
	 */
	protected $annotationReader;

	/**
	 * Constructor of the class
	 */
	public function __construct()
	{
		AnnotationRegistry::registerLoader('class_exists');

		$this->annotationReader = new SimpleAnnotationReader();
		$this->annotationReader->addNamespace('Sunrise\Http\Router\Annotation');
	}

	/**
	 * @param string $destination
	 * @param string $filter
	 *
	 * @return \SplFileInfo[]
	 */
	protected function findFiles(string $destination, string $filter = '/\.php$/i') : array
	{
		$directory = new \RecursiveDirectoryIterator($destination);
		$iterator = new \RecursiveIteratorIterator($directory);
		$files = new \RegexIterator($iterator, $filter);

		return \iterator_to_array($files);
	}

	/**
	 * @param string $destination
	 *
	 * @return \ReflectionClass[]
	 */
	protected function findClasses(string $destination) : array
	{
		$files = $this->findFiles($destination);
		$classes = \get_declared_classes();

		foreach ($files as $file) {
			require_once $file->getRealPath();
		}

		return \array_map(function(string $className) {
			return new \ReflectionClass($className);
		}, \array_diff(\get_declared_classes(), $classes));
	}

	/**
	 * @param string $destination
	 * @param string $className
	 *
	 * @return array
	 */
	protected function findAnnotations(string $destination, string $className) : array
	{
		$classes = $this->findClasses($destination);
		$annotations = [];

		foreach ($classes as $class) {
			$annotation = $this->annotationReader->getClassAnnotation($class, $className);

			if (null === $annotation) {
				continue;
			}

			$annotation->source = $class;
			$annotations[] = $annotation;
		}

		return $annotations;
	}
}
