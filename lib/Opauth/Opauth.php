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
use Opauth\HttpClient;
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
	 * Absolute path to strategy dir with trailing slash
	 * Not required when using composer installs or having the strategies in lib/Opauth/Provider/ directory
	 *
	 * @var string
	 */
	protected $strategyDir;

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
		if (!empty($config['strategy_dir'])) {
			$this->strategyDir = $config['strategy_dir'];
		}

		$path = null;
		if (!empty($config['path'])) {
			$path = $config['path'];
		}
		if (!empty($config['http_client_method'])) {
			HttpClient::$method = $config['http_client_method'];
		}
		$this->Request = new Request($path);
	}

	/**
	 * Run Opauth:
	 * Parses request URI and perform defined authentication actions based based on it.
	 */
	public function run() {
		if (!$this->Request->provider) {
			return false;
		}
		$this->loadStrategy();

		if (is_null($this->Request->action)) {
			return $this->Strategy->request();
		}

		if ($this->Request->action !== 'callback') {
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

		if (empty($settings['name'])) {
			$settings['name'] = $name;
		}

		// Define a URL-friendly name
		if (empty($settings['url_name'])) {
			$settings['url_name'] = strtolower($name);
		}

		$this->strategies[$settings['url_name']] = $settings;
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
		if (!array_key_exists($this->Request->provider, $this->strategies)) {
			throw new Exception('Unsupported or undefined Opauth strategy - ' . $this->Request->provider);
		}

		$strategy = $this->strategies[$this->Request->provider];
		$class = '\Opauth\Provider\\' . $strategy['name'] . '\\' . 'Strategy';
		if ($this->strategyDir) {
			require_once $this->strategyDir . $strategy['name'] . DIRECTORY_SEPARATOR . 'Strategy.php';
		}
		$this->setStrategy(new $class($this->Request, $strategy));
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