<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2013 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth;
use \Exception;

/**
 * Opauth Request
 * Parses current request parameters
 *
 * @package      Opauth
 */
class Request {

	/**
	 * Strategy urlname, used to switch to correct strategy
	 *
	 * @var string
	 */
	public $urlname = null;

	/**
	 * Action, null for request, 'callback' for callback
	 *
	 * @var string
	 */
	public $action = null;

	/**
	 * Opauth url path, relative to host
	 *
	 * @var string
	 */
	protected $path = '/auth/';

	/**
	 * Set path if '/auth/' isnt the default path, or if application is in a subdir
	 *
	 * @param string $path
	 */
	public function __construct($path = null) {
		if ($path) {
			$this->path = $path;
		}
		$this->parseUri();
	}

	/**
	 * Get strategy url_name and action form the request
	 *
	 * @throws Exception
	 */
	private function parseUri() {
		if (strpos($_SERVER['REQUEST_URI'], $this->path) === false) {
			throw new Exception('Not an Opauth request, path is not in uri');
		}
		$request = substr($_SERVER['REQUEST_URI'], strlen($this->path) - 1);

		preg_match_all('/\/([A-Za-z0-9-_]+)/', $request, $matches);
		if (!empty($matches[1][0])) {
			$this->urlname = $matches[1][0];
		}
		if (!empty($matches[1][1])) {
			$this->action = $matches[1][1];
		}
	}

	/**
	 * getHost
	 *
	 * @return string Full host string
	 */
	public function getHost() {
		return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
	}

	/**
	 * providerUrl
	 *
	 * @return string Full path to provider url_name
	 */
	public function providerUrl() {
		return $this->getHost() . $this->path . $this->urlname;
	}
}