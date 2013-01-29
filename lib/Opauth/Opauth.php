<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth
 * @license      MIT License
 */

/**
 * Opauth
 * Multi-provider authentication framework for PHP
 * 
 * @package			Opauth
 */
class Opauth {
	/**
	 * User configurable settings
	 * Refer to example/opauth.conf.php.default or example/opauth.conf.php.advanced for sample
	 * More info: https://github.com/uzyn/opauth/wiki/Opauth-configuration
	 */
	public $config;
	
	/**
	 * Environment variables
	 */
	public $env;
	
	/** 
	 * Strategy map: for mapping URL-friendly name to Class name
	 */
	public $strategyMap;
	
	/**
	 * Constructor
	 * Loads user configuration and strategies.
	 * 
	 * @param array $config User configuration
	 * @param boolean $run Whether Opauth should auto run after initialization.
	 */
	public function __construct($config = array(), $run = true) {
		/**
		 * Configurable settings
		 */
		$this->config = array_merge(array(
			'host' => ((array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'],
			'path' => '/',
			'callback_url' => '{path}callback',
			'callback_transport' => 'session',
			'debug' => false,
			
			/**
		 	* Security settings
		 	*/
			'security_salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
			'security_iteration' => 300,
			'security_timeout' => '2 minutes'
		), $config);
		
		/**
		 * Environment variables, including config
		 * Used mainly as accessors
		 */
		$this->env = array_merge(array(
			'request_uri' => $_SERVER['REQUEST_URI'],
			'complete_path' => $this->config['host'].$this->config['path'],
			'lib_dir' => dirname(__FILE__).'/',
			'strategy_dir' => dirname(__FILE__).'/Strategy/'
		), $this->config);
		
		if (!class_exists('OpauthStrategy')) {
			require $this->env['lib_dir'].'OpauthStrategy.php';
		}
		
		foreach ($this->env as $key => $value) {
			$this->env[$key] = OpauthStrategy::envReplace($value, $this->env);
		}
	
		if ($this->env['security_salt'] == 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m'){
			trigger_error('Please change the value of \'security_salt\' to a salt value specific to your application', E_USER_NOTICE);
		}
		
		$this->loadStrategies();
		
		if ($run) {
			$this->run();
		}
	}
	
	/**
	 * Run Opauth:
	 * Parses request URI and perform defined authentication actions based based on it.
	 */
	public function run() {
		$this->parseUri();
		
		if (!empty($this->env['params']['strategy'])) {
			if (strtolower($this->env['params']['strategy']) == 'callback') {
				$this->callback();
			} elseif (array_key_exists($this->env['params']['strategy'], $this->strategyMap)) {
				$name = $this->strategyMap[$this->env['params']['strategy']]['name'];
				$class = $this->strategyMap[$this->env['params']['strategy']]['class'];
				$strategy = $this->env['Strategy'][$name];

				// Strip out critical parameters
				$safeEnv = $this->env;
				unset($safeEnv['Strategy']);
				
				$actualClass = $this->requireStrategy($class);
				$this->Strategy = new $actualClass($strategy, $safeEnv);
				
				if (empty($this->env['params']['action'])) {
					$this->env['params']['action'] = 'request';
				}
				
				$this->Strategy->callAction($this->env['params']['action']);
			} else {
				trigger_error('Unsupported or undefined Opauth strategy - '.$this->env['params']['strategy'], E_USER_ERROR);
			}
		} else {
			$sampleStrategy = array_pop($this->env['Strategy']);
			trigger_error('No strategy is requested. Try going to '.$this->env['complete_path'].$sampleStrategy['strategy_url_name'].' to authenticate with '.$sampleStrategy['strategy_name'], E_USER_NOTICE);
		}
	}
	
	/**
	 * Parses Request URI
	 */
	private function parseUri() {
		$this->env['request'] = substr($this->env['request_uri'], strlen($this->env['path']) - 1);
		
		if (preg_match_all('/\/([A-Za-z0-9-_]+)/', $this->env['request'], $matches)) {
			foreach ($matches[1] as $match) {
				$this->env['params'][] = $match;
			}
		}
		
		if (!empty($this->env['params'][0])) {
			$this->env['params']['strategy'] = $this->env['params'][0];
		}
		if (!empty($this->env['params'][1])) {
			$this->env['params']['action'] = $this->env['params'][1];
		}
	}
	
	/**
	 * Load strategies from user-input $config
	 */	
	private function loadStrategies() {
		if (isset($this->env['Strategy']) && is_array($this->env['Strategy']) && count($this->env['Strategy']) > 0) {
			foreach ($this->env['Strategy'] as $key => $strategy) {
				if (!is_array($strategy)) {
					$key = $strategy;
					$strategy = array();
				}
				
				$strategyClass = $key;
				if (array_key_exists('strategy_class', $strategy)) {
					$strategyClass = $strategy['strategy_class'];
				} else {
					$strategy['strategy_class'] = $strategyClass;
				}
				
				$strategy['strategy_name'] = $key;
				
				// Define a URL-friendly name
				if (empty($strategy['strategy_url_name'])) {
					$strategy['strategy_url_name'] = strtolower($key);
				}
				
				$this->strategyMap[$strategy['strategy_url_name']] = array(
					'name' => $key,
					'class' => $strategyClass
				);
				
				$this->env['Strategy'][$key] = $strategy;
			}
		} else {
			trigger_error('No Opauth strategies defined', E_USER_ERROR);
		}
	}
		
	/**
	 * Validate $auth response
	 * Accepts either function call or HTTP-based call
	 * 
	 * @param string $input = sha1(print_r($auth, true))
	 * @param string $timestamp = $_REQUEST['timestamp'])
	 * @param string $signature = $_REQUEST['signature']
	 * @param string $reason Sets reason for failure if validation fails
	 * @return boolean true: valid; false: not valid.
	 */
	public function validate($input = null, $timestamp = null, $signature = null, &$reason = null) {
		$functionCall = true;
		if (!empty($_REQUEST['input']) && !empty($_REQUEST['timestamp']) && !empty($_REQUEST['signature'])) {
			$functionCall = false;
			$provider = $_REQUEST['input'];
			$timestamp = $_REQUEST['timestamp'];
			$signature = $_REQUEST['signature'];
		}
		
		$timestamp_int = strtotime($timestamp);
		if ($timestamp_int < strtotime('-'.$this->env['security_timeout']) || $timestamp_int > time()) {
			$reason = "Auth response expired";
			return false;
		}
		
		$hash = OpauthStrategy::hash($input, $timestamp, $this->env['security_iteration'], $this->env['security_salt']);
		
		if (strcasecmp($hash, $signature) !== 0) {
			$reason = "Signature does not validate";
			return false;
		}
		
		return true;
	}
	
	/**
	 * Callback: prints out $auth values, and acts as a guide on Opauth security
	 * Application should redirect callback URL to application-side.
	 * Refer to example/callback.php on how to handle auth callback.
	 */
	public function callback() {
		echo "<strong>Note: </strong>Application should set callback URL to application-side for further specific authentication process.\n<br>";
		
		$response = null;
		switch($this->env['callback_transport']) {
			case 'session':
				if (!session_id()) {
					session_start();
					$response = $_SESSION['opauth'];
					unset($_SESSION['opauth']);
				}
				break;
			case 'post':
				$response = unserialize(base64_decode( $_POST['opauth'] ));
				break;
			case 'get':
				$response = unserialize(base64_decode( $_GET['opauth'] ));
				break;
			default:
				echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
				break;
		}
		
		
		if (array_key_exists('error', $response)) {  // Check if it's an error callback
			echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.'."<br>\n";
		} else { // No it isn't. Proceed with auth validation
			if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
				echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.'."<br>\n";
			} elseif (!$this->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
				echo '<strong style="color: red;">Invalid auth response: </strong>'.$reason.".<br>\n";
			} else {
				echo '<strong style="color: green;">OK: </strong>Auth response is validated.'."<br>\n";
			}
		}		
		
		/**
		 * Auth response dump
		 */
		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}
	
	/**
	 * Loads a strategy, firstly check if the
	 *  strategy's class already exists, especially for users of Composer;
	 * If it isn't, attempts to load it from $this->env['strategy_dir']
	 * 
	 * @param string $strategy Name of a strategy
	 * @return string Class name of the strategy, usually StrategyStrategy
	 */
	private function requireStrategy($strategy) {
		if (!class_exists($strategy.'Strategy')) {
			// Include dir where Git repository for strategy is cloned directly without 
			// specifying a dir name, eg. opauth-facebook
			$directories = array(
				$this->env['strategy_dir'].$strategy.'/',
				$this->env['strategy_dir'].'opauth-'.strtolower($strategy).'/',
				$this->env['strategy_dir'].strtolower($strategy).'/',
				$this->env['strategy_dir'].'Opauth-'.$strategy.'/'
			);
			
			// Include deprecated support for strategies without Strategy postfix as class name or filename
			$classNames = array(
				$strategy.'Strategy',
				$strategy
			);
			
			$found = false;
			foreach ($directories as $dir) {
				foreach ($classNames as $name) {
					if (file_exists($dir.$name.'.php')) {
						$found = true;
						require $dir.$name.'.php';
						return $name;
					}
				}
			}
			
			if (!$found) {
				trigger_error('Strategy class file ('.$this->env['strategy_dir'].$strategy.'/'.$strategy.'Strategy.php'.') is missing', E_USER_ERROR);
			}
		}
		return $strategy.'Strategy';
	}
	
	/**
	 * Prints out variable with <pre> tags
	 * Silence if Opauth is not in debug mode
	 * 
	 * @param mixed $var Object or variable to be printed
	 */	
	public function debug($var) {
		if ($this->env['debug'] !== false) {
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}
	}
}