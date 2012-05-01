<?php
/**
 * Opauth Strategy
 * - Individual strategies to be extended from this class
 *
 */
class OpauthStrategy{	
	
/**
 * Compulsory config keys, listed as unassociative arrays
 * eg. array('app_id', 'app_secret');
 */
	public $expects;
	
/**
 * Optional config keys with respective default values, listed as associative arrays
 * eg. array('scope' => 'email');
 */
	public $defaults;
	
/**
 * Auth array, containing results after successful authentication
 */
	public $auth;
	
	public $name;
	protected $Opauth;

/**
 * Configurations and settings unique to a particular strategy
 */
	protected $strategy;
	
	public function __construct(&$Opauth, $strategy){
		$this->Opauth = $Opauth;
		$this->strategy = $strategy;
		
		$this->name = $strategy['name'];
		
		if (is_array($this->expects)){
			foreach ($this->expects as $key){
				$this->expects($key);
			}
		}
		
		if (is_array($this->defaults)){
			foreach ($this->defaults as $key => $value){
				$this->optional($key, $this->Opauth->envReplace($value));
			}
		}
	}
	
/**
 * Auth request
 * aka Log in or Register
 */
	public function request(){
	}
	
/**
 * Packs $auth nicely and send to callback_uri
 * TODO: adds POST support as GET params may encounter browser limit on URL length
 * TODO: adds pass by session support as well if domain of callback_uri is the same as Opauth's
 * 
 */
	public function callback(){
		$timestamp = date('c');
		
		// Object doesn't translate very well when going through HTTP
		$this->auth = $this->recursiveGetObjectVars($this->auth);
		
		$params = array(
			'auth' => $this->auth, 
			'timestamp' => $timestamp,
			'signature' => $this->sign($timestamp)
		);
		
		$transport = $this->Opauth->env['callback_transport'];
		
		switch($transport){
			case 'session':
				session_start();
				$_SESSION['auth'] = $params;
				$this->redirect($this->Opauth->env['callback_uri']);
				break;
			case 'get':
			default:
				$this->redirect($this->Opauth->env['callback_uri'].'?'.http_build_query($params));
		}
	}
	
/**
 * Call an action from a defined strategy
 *
 * @param string $action Action name to call
 * @param string $defaultAction If an action is not defined in a strategy, calls $defaultAction
 */
	public function callAction($action, $defaultAction = 'request'){
		if (method_exists($this, $action)) return $this->{$action}();
		else return $this->{$defaultAction}();
	}
	
/**
 * Ensures that a compulsory value is set, throws an error if it's not set
 * 
 * @return mixed The loaded value
 */
	protected function expects($key, $not = null){
		if (!array_key_exists($key, $this->strategy)){
			trigger_error($this->name." config value ($key) expected.", E_USER_ERROR);
			exit();
		}
		
		$value = $this->strategy[$key];
		if (empty($value) || $value == $not){
			trigger_error($this->name." config value ($key) expected.", E_USER_ERROR);
			exit();
		}
		
		return $value;
	}
	
/**
 * Loads a default value into $strategy if the associated key is not found
 * 
 * @return mixed The loaded value
 */
	protected function optional($key, $default = null){
		if (!array_key_exists($key, $this->strategy)){
			$this->strategy[$key] = $default;
			return $default;
		}
		
		else return $this->strategy[$key];
	}
		
/**
 * Redirect to $url with HTTP header
 */
	protected function redirect($url, $exit = true){
		header("Location: $url");
		if ($exit) exit();
	}
	
/**
 * Simple HTTP request with file_get_contents
 * - Reluctant to use cURL at the moment for not wanting to add an additional dependency unless necessary
 * 
 * @param $url Full URL to load
 * @param $options array with context of file_get_contents
 * @return string Content of destination, without headers
 */
	protected function httpRequest($url, $options = null){
		$context = null;
		if (!empty($options) && is_array($options)){
			$context = stream_context_create($options);
		}
		
		return file_get_contents($url, false, $context);
	}
	
/**
 * Security: Sign $auth before redirecting to callback_uri
 * 
 * @param $timestamp ISO 8601 formatted date
 * @return string Resulting signature
 */
	protected function sign($timestamp = null){
		if (is_null($timestamp)) $timestamp = date('c');
		
		$input = sha1(print_r($this->auth, true));
		$hash = $this->hash($input, $timestamp, $this->Opauth->env['Security.iteration'], $this->Opauth->env['Security.salt']);
		
		return $hash;
	}
	
/**
 * Static hashing funciton
 * 
 * @param string $input Input string
 * @param string $timestamp ISO 8601 formatted date * 
 * @param int $iteration Number of hash interations
 * @param string $salt
 * @return string Resulting hash
 */
	public static function hash($input, $timestamp, $iteration, $salt){
		$iteration = intval($iteration);
		if ($iteration <= 0) return false;
		
		for ($i = 0; $i < $iteration; ++$i) $input = base_convert(sha1($input.$salt.$timestamp), 16, 36);
		return $input;	
	}

/**
* Recursively converts object into array
*/
	public static function recursiveGetObjectVars($obj){
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val){
			$val = (is_array($val) || is_object($val)) ? self::recursiveGetObjectVars($val) : $val;
			$arr[$key] = $val;
		}
		
		return $arr;
	}
}