<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2013 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Test;

/**
 * AutoLoader
 *
 * @package       Opauth
 */
class AutoLoader {

	/**
	 * Base directory
	 *
	 * @var string
	 */
	private $directory;

	/**
	 * Namespace
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * String length of namespace
	 *
	 * @var integer
	 */
	private $namespaceLength;

	/**
	 * Constructor
	 *
	 * @param string $namespace
	 * @param string $baseDirectory
	 */
	public function __construct($namespace = null, $baseDirectory = null) {
		if (!$namespace) {
			$namespace = __NAMESPACE__;
		}
		if (!$baseDirectory) {
			$baseDirectory = __DIR__;
		}
		$this->namespace = $namespace . '\\';
		$this->namespaceLength = strlen($this->namespace);
		$this->directory = $baseDirectory;
	}

	/**
	 * Register autoloader
	 *
	 */
	public static function register($namespace = null, $dir = null) {
		spl_autoload_register(array(new self($namespace, $dir), 'loadClass'));
	}

	/**
	 * Unregister autoloader
	 *
	 */
	public static function unregister($namespace = null, $dir = null) {
		spl_autoload_unregister(array(new self($namespace, $dir), 'loadClass'));
	}

	/**
	 * Loads the class
	 *
	 * @param string $className The name of the class to load.
	 */
	public function loadClass($className) {
		if (strpos($className, $this->namespace) === 0) {
			$path = $this->directory . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, substr($className, $this->namespaceLength)) . '.php';
			if (file_exists($path)) {
				require $path;
			}
		}
	}

}
