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

/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @package			Opauth
 */
class Opauth {

	/**
	 * Strategy instance
	 *
	 * @var Strategy
	 */
	protected $strategy;

	/**
	 * Holds array of strategy settings indexed by url_name
	 *
	 * @var array
	 */
	protected $strategies = array();

	/**
	 * The request object
	 *
	 * @var Request
	 */
	public $request;

	/**
	 * The response object
	 *
	 * @var Response
	 */
	public $response;

	/**
	 * Configuration array
	 *
	 * @var array
	 */
	protected $config = array(
		'http_transport' => "\\Opauth\\Transport\\Curl",
		'callback' => 'callback',
		'path' => '/auth/',
		'strategyDir' => null
	);

	/**
	 * Constructor
	 * Loads user configuration and strategies.
	 *
	 * @param array $config User configuration
	 * @return void
	 */
	public function __construct($config = array()) {
		if (isset($config['Strategy'])) {
			$this->buildStrategies($config['Strategy']);
			unset($config['Strategy']);
		}
		$this->config = array_merge($this->config, $config);

		$this->request = new Request($this->config('path'));
	}

	/**
	 * Get key from config array, null if not present
	 *
	 * @param type $key Configuration key
	 * @return mixed Config value
	 */
	protected function config($key) {
		if (!isset($this->config[$key])) {
			return null;
		}
		return $this->config[$key];
	}

	/**
	 * Run Opauth:
	 * Parses request URI and perform defined authentication actions based based on it.
	 * When running `request()` it will do a redirect, `callback` returns Opauth Response object
	 *
	 * @return Response Response object for callback
	 * @throws Exception
	 */
	public function run() {
		if (!$this->request->urlname) {
			throw new \Exception('No strategy found in url');
		}

		$this->loadStrategy();
		if (!$this->request->action) {
			return $this->request();
		}

		if ($this->request->action !== $this->config('callback')) {
			throw new \Exception('Invalid callback url element: ' . $this->request->action);
		}
		return $this->callback();
	}

	/**
	 * Run request method on current strategy
	 *
	 * @throws Exception
	 */
	protected function request() {
		$this->response = $response = $this->strategy->request();
		if (!$response instanceof Response || !$response->isError()) {
			throw new \Exception('Strategy request should redirect or return Response with error');
		}
		throw new \Exception($response->errorMessage());
	}

	/**
	 *
	 * @return \Opauth\Response
	 * @throws Exception
	 */
	protected function callback() {
		$this->response = $response = $this->strategy->callback();
		if (!$response instanceof \Opauth\Response) {
			throw new \Exception('Response should be instance of Opauth\Response');
		}
		if ($response->isError()) {
			throw new \Exception($response->errorMessage());
		}
		$response->map();
		if (!$response->isValid()) {
			throw new \Exception('Invalid response, missing required parameters');
		}
		return $response;
	}

	/**
	 * Builds strategies
	 *
	 * @param array $strategies Array of strategies and their settings
	 * @return true
	 * @throws Exception
	 */
	public function buildStrategies($strategies) {
		if (!$strategies || !is_array($strategies)){
			throw new \Exception('No strategies found');
		}

		foreach ($strategies as $name => $settings) {
			$this->buildStrategy($name, $settings);
		}
		return true;
	}

	/**
	 * Builds single strategy array
	 *
	 * @param string|integer $name
	 * @param array|string $settings
	 * @return array Settings array
	 */
	public function buildStrategy($name, $settings) {
		if (!is_array($settings)) {
			$name = $settings;
			$settings = array();
		}

		$settings['provider'] = $name;

		if (empty($settings['_name'])) {
			$settings['_name'] = $name;
		}

		// Define a URL-friendly name
		if (empty($settings['_url_name'])) {
			$settings['_url_name'] = strtolower($name);
		}
		if (!isset($settings['_enabled'])) {
			$settings['_enabled'] = true;
		}

		return $this->strategies[$settings['_url_name']] = $settings;
	}

	/**
	 * Loads strategy based on url if not manually set
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function loadStrategy() {
		if (!empty($this->strategy)) {
			return null;
		}
		if (!$this->strategies) {
			throw new \Exception('No strategies configured');
		}
		if (!array_key_exists($this->request->urlname, $this->strategies)) {
			throw new \Exception('Unsupported or undefined Opauth strategy - ' . $this->request->urlname);
		}

		$settings = $this->strategies[$this->request->urlname];
		if (!$settings['_enabled']) {
			throw new \Exception('This strategy is not enabled');
		}
		$classname = '\Opauth\Strategy\\' . $settings['_name'] . '\\' . 'Strategy';
		if ($dir = $this->config('strategyDir') && is_dir($dir)) {
			\Opauth\AutoLoader::register('Opauth\\Strategy', $dir);
		}
		if (!class_exists($classname)) {
			throw new \Exception(sprintf('Strategy class %s not found', $classname));
		}
		$this->setStrategy(new $classname($settings));
		$this->strategy->callbackUrl($this->request->providerUrl() . '/' . $this->config('callback'));
		$Transport = $this->config('http_transport');
		$this->strategy->setTransport(new $Transport);
	}

	/**
	 * Sets Strategy instance
	 *
	 * @param \Opauth\AbstractStrategy $strategy
	 */
	public function setStrategy(\Opauth\AbstractStrategy $strategy) {
		$this->strategy = $strategy;
	}

}