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
use Opauth\AutoLoader;
use Opauth\Transport\TransportInterface;
use \Exception;

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
	protected $Strategy;

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
	public $Request;

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
	 */
	public function __construct($config = array()) {
		if (isset($config['Strategy'])) {
			$this->buildStrategies($config['Strategy']);
			unset($config['Strategy']);
		}
		$this->config = array_merge($this->config, $config);

		if (!HttpClient::transport() instanceof TransportInterface) {
			$Transport = $this->config('http_transport');
			HttpClient::transport(new $Transport);
		}
		$this->Request = new Request($this->config('path'));
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
	 */
	public function run() {
		if (!$this->Request->urlname) {
			return false;
		}
		$this->loadStrategy();

		if (!$this->Request->action) {
			return $this->Strategy->request();
		}

		if ($this->Request->action !== $this->config('callback')) {
			throw new Exception('Invalid callback url element: ' . $this->Request->action);
		}
		$response = $this->Strategy->callback();
		if (!$response instanceof Response) {
			throw new Exception('Response should be instance of Opauth\Response');
		}
		$response->map();
		if (!$response->isValid()) {
			throw new Exception('Invalid response, missing required parameters');
		}
		return $response;
	}

	/**
	 * Builds strategies
	 *
	 * @param array $strategies Array of strategies and their settings
	 * @return void
	 */
	public function buildStrategies($strategies) {
		if (!$strategies || !is_array($strategies)){
			throw new Exception('No strategies found');
		}

		foreach ($strategies as $strategy => $settings) {
			$this->buildStrategy($strategy, $settings);
		}
	}

	/**
	 * Builds single strategy array
	 *
	 * @param string|integer $name
	 * @param array|string $settings
	 * @return void
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

		$this->strategies[$settings['_url_name']] = $settings;
	}

	/**
	 * Loads strategy based on url if not manually set
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function loadStrategy() {
		if (!empty($this->Strategy)) {
			return null;
		}
		if (!$this->strategies) {
			throw new Exception('No strategies configured');
		}
		if (!array_key_exists($this->Request->urlname, $this->strategies)) {
			throw new Exception('Unsupported or undefined Opauth strategy - ' . $this->Request->urlname);
		}

		$strategy = $this->strategies[$this->Request->urlname];
		$class = '\Opauth\Strategy\\' . $strategy['_name'] . '\\' . 'Strategy';
		if ($dir = $this->config('strategyDir') && is_dir($dir)) {
			AutoLoader::register('Opauth\\Strategy', $dir);
		}
		if (!class_exists($class)) {
			throw new Exception(sprintf('Strategy class %s not found', $class));
		}
		$this->setStrategy(new $class($strategy));
		$this->Strategy->callbackUrl($this->Request->providerUrl() . '/' . $this->config('callback'));
	}

	/**
	 * Sets Strategy instance
	 *
	 * @param \Opauth\StrategyInterface $Strategy
	 */
	public function setStrategy(StrategyInterface $Strategy) {
		$this->Strategy = $Strategy;
	}

}