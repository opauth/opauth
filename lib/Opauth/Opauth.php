<?php
/**
 * Opauth core
 *
 */
class Opauth{
/**
 * User configuraable settings
 *
 * - Do not refer to this anywhere in logic, except in __construct() of Opauth
 * - TODO: Documentation on config
 */
	public $config;

/**
 * Environment variables
 */
	public $env;

/**
 * Defined strategies
 * - array key	: URL-friendly name, preferably all lowercase
 * - name		: Class and file name. If unset, Opauth automatically capitalize first letter as name
 *
 * eg. array( 'facebook', 'flickr' );
 * eg. array( 'foobar' => array( 'name' => 'FooBar') );
 *
 */
	public $strategies;

	public function __construct($config = array()){

	/**
	 * Configurable settings
	 */
		$this->config = array_merge(array(
			'host' => ((array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'],
			'path' => '/',
			'callback_uri' => '{path}callback',
			'callback_transport' => 'session',
			'debug' => false,

		/**
		 * Security settings
		 */
			'Security.salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
			'Security.iteration' => 300,
			'Security.timeout' => '2 minutes'

		), $config);

	/**
	 * Environment variables, including config
	 * Used mainly as accessors
	 */
		$this->env = array_merge(array(
			'uri' => $_SERVER['REQUEST_URI'],
			'complete_path' => $this->config['host'].$this->config['path'],
			'lib_dir' => dirname(__FILE__).'/',
			'strategy_dir' => dirname(__FILE__).'/Strategy/'
		), $this->config);

		foreach ($this->env as $key => $value){
			$this->env[$key] = $this->envReplace($value);
		}

		if ($this->env['Security.salt'] == 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m'){
			trigger_error('Please change the value of \'Security.salt\' to a salt value specific to your application', E_USER_NOTICE);
		} elseif (empty($this->env['Security.salt'])) {
			trigger_error('You cannot leave the value of \'Security.salt\' empty', E_USER_NOTICE);
		}

		$this->_loadStrategies();
		$this->_parseUri();

		/* Run */
		if (!empty($this->env['strategy'])){
			if (strtolower($this->env['strategy']) == 'callback'){
				$this->callback();
			}
			elseif (array_key_exists($this->env['strategy'], $this->strategies)){
				$strategy = $this->strategies[$this->env['strategy']];
				require $this->env['lib_dir'].'OpauthStrategy.php';
				require $this->env['strategy_dir'].$strategy['name'].'/'.$strategy['name'].'.php';

				$this->Strategy = new $strategy['name']($this, $strategy);
				$this->Strategy->callAction($this->env['action']);
			}
			else{
				trigger_error('Unsupported or undefined Opauth strategy - '.$this->env['strategy'], E_USER_ERROR);
			}
		}
	}

/**
 * Parses Request URI
 */
	protected function _parseUri(){
		$this->env['request'] = substr($this->env['uri'], strlen($this->env['path']) - 1);

		if (preg_match_all('/\/([A-Za-z0-9-_]+)/', $this->env['request'], $matches)){
			foreach ($matches[1] as $match){
				$this->env['params'][] = $match;
			}
		}

		if (!empty($this->env['params'][0])) $this->env['strategy'] = $this->env['params'][0];
		if (!empty($this->env['params'][1])) $this->env['action'] = $this->env['params'][1];
	}

/**
 * Load strategies from user-input $config
 */
	protected function _loadStrategies(){
		if (isset($this->env['strategies']) && is_array($this->env['strategies']) && count($this->env['strategies']) > 0){
			foreach ($this->env['strategies'] as $key => $strategy){
				if (!is_array($strategy)){
					$key = $strategy;
					$strategy = array();
				}

				if (empty($strategy['name'])) $strategy['name'] = strtoupper(substr($key, 0, 1)).substr($key, 1);
				$this->strategies[$key] = $strategy;
			}
		}
		else{
			trigger_error('No Opauth strategies defined', E_USER_ERROR);
		}
	}

/**
 * Replace defined env values enclused in {} with actual values
 */
	public function envReplace($value){
		if (is_string($value) && preg_match_all('/{([A-Za-z0-9-_]+)}/', $value, $matches)){
			foreach ($matches[1] as $key){
				if (array_key_exists($key, $this->env)){
					$value = str_replace('{'.$key.'}', $this->env[$key], $value);
				}
			}

			return $value;
		}

		return $value;
	}


/**
 * Callback: prints out $auth values, and acts as a guide on Opauth security
 * Application should redirect callback URL to application-side.
 */
	public function callback(){
		echo "<strong>Note: </strong>Application should set callback URL to application-side for further specific authentication process.\n<br>";

	/**
	* Fetch auth
	*/
		$response = null;
		switch($this->env['callback_transport']){
			case 'session':
				session_start();
				$response = $_SESSION['opauth'];
				break;
			case 'post':
				$response = $_POST;
				break;
			case 'get':
				$response = $_GET;
				break;
			default:
				echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
				break;
		}

	/**
	 * Validation
	 */
		if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])){
			echo '<strong style="color: red;">Invalid auth: </strong>Missing key auth components.'."<br>\n";
		}
		elseif (!$this->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)){
			echo '<strong style="color: red;">Invalid auth: </strong>'.$reason.".<br>\n";
		}
		else{
			echo '<strong style="color: green;">OK: </strong>Auth is validated.'."<br>\n";
		}


	/**
	 * Auth response dump
	 */
		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}

/**
 * Validate $auth response
 * Accepts either function call or HTTP-based call
 *
 * @param string $input = sha1(print_r($auth, true))
 * @param string $timestamp = $_REQUEST['timestamp'])
 * @param string $signature = $_REQUEST['signature']
 * @param $reason Sets reason for failure if validation fails
 * @return boolean
 *
 * TODO: Accepts validate calls via POST/GET
 */
	public function validate($input = null, $timestamp = null, $signature = null, &$reason = null){
		$functionCall = true;
		if (!empty($_REQUEST['input']) && !empty($_REQUEST['timestamp']) && !empty($_REQUEST['signature'])){
			$functionCall = false;
			$provider = $_REQUEST['input'];
			$timestamp = $_REQUEST['timestamp'];
			$signature = $_REQUEST['signature'];
		}

		$timestamp_int = strtotime($timestamp);
		if ($timestamp_int < strtotime('-'.$this->env['Security.timeout']) || $timestamp_int > time()){
			$reason = "Auth response expired";
			return false;
		}

		require $this->env['lib_dir'].'OpauthStrategy.php';
		$hash = OpauthStrategy::hash($input, $timestamp, $this->env['Security.iteration'], $this->env['Security.salt']);

		if (strcasecmp($hash, $signature) !== 0){
			$reason = "Signature does not validate";
			return false;
		}

		return true;
	}


/**
 * Prints out variable with <pre> tags
 * - If debug is false, no printing
 */
	public function debug($var){
		if ($this->env['debug'] !== false){
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}
	}
}
