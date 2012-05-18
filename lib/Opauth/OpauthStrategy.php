<?php
/**
 * Opauth Strategy
 * Individual strategies are to be extended from this class
 *
 * @copyright		Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link 			http://opauth.org
 * @package			Opauth.Strategy
 * @license			MIT License
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
	 * Auth response array, containing results after successful authentication
	 */
	public $auth;
	
	/**
	 * Name of strategy
	 */
	public $name = null;
	
	/**
	 * Pointer to Opauth instance
	 */
	protected $Opauth;

	/**
	 * Configurations and settings unique to a particular strategy
	 */
	protected $strategy;
	
	public function __construct(&$Opauth, $strategy){
		$this->Opauth = $Opauth;
		$this->strategy = $strategy;
		
		// Include some useful values from Opauth's env
		$this->strategy['_opauth_callback_url'] = $this->Opauth->env['host'].$this->Opauth->env['Callback.uri'];
		
		if ($this->name === null){
			$this->name = (isset($name) ? $name : get_class($this));
		}
		
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
	 * Packs $auth nicely and send to Callback.uri, ships $auth either via GET, POST or session.
	 * Set shipping transport via Callback.transport config, default being session.
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
		
		$this->shipToCallback($params);
	}
	
	/**
	 * Error callback
	 * 
	 * @param array $error Data on error to be sent back along with the callback
	 *   $error = array(
	 *     'provider'	// Provider name
	 *     'code'		// Error code, can be int (HTTP status) or string (eg. access_denied)
	 *     'message'	// User-friendly error message
	 *     'raw'		// Actual detail on the error, as returned by the provider
	 *   )
	 * 
	 */
	protected function errorCallback($error){
		$timestamp = date('c');
		
		$params = array(
			'error' => $this->recursiveGetObjectVars($error),
			'timestamp' => $timestamp
		);
		
		$this->shipToCallback($params);
	}
	
	/**
	 * Send $data to Callback.uri using specified transport method
	 * 
	 * @param array $data Data to be sent
	 * @param string $transport Callback method, either 'get', 'post' or 'session'
	 *        'session': Default. Works best unless Callback.uri is on a different domain than Opauth
	 *        'post': Works cross-domain, but relies on availability of client-side JavaScript.
	 *        'get': Works cross-domain, but may be limited or corrupted by browser URL length limit 
	 *               (eg. IE8/IE9 has 2083-char limit)
	 * 
	 */
	private function shipToCallback($data, $transport = null){
		if (empty($transponrt)) $transport = $this->Opauth->env['Callback.transport'];
		
		switch($transport){
			case 'get':
				$this->redirect($this->Opauth->env['Callback.uri'].'?'.http_build_query($data));
				break;
			case 'post':
				$this->clientPost($this->Opauth->env['Callback.uri'], $data);
				break;
			case 'session':
			default:			
				session_start();
				$_SESSION['opauth'] = $data;
				$this->redirect($this->Opauth->env['Callback.uri']);
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
			trigger_error($this->name." config parameter for \"$key\" expected.", E_USER_ERROR);
			exit();
		}
		
		$value = $this->strategy[$key];
		if (empty($value) || $value == $not){
			trigger_error($this->name." config parameter for \"$key\" expected.", E_USER_ERROR);
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
	 * Security: Sign $auth before redirecting to Callback.uri
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
	 * *****************************************************
	 * Utilities
	 * A collection of static functions for strategy's use
	 * *****************************************************
	 */
	
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
	 * Redirect to $url with HTTP header
	 */
	protected static function redirect($url, $exit = true){
		header("Location: $url");
		if ($exit) exit();
	}

	/**
	 * Generates a simple HTML form with $params initialized and post results via JavaScript
	 * 
	 * @param $url string URL to be POSTed
	 * @param $params array Data to be POSTed
	 */
	protected static function clientPost($url, $params = array()){
		$html = '<html><body onload="postit();"><form name="auth" method="post" action="'.$url.'">';
		
		if (!empty($params) && is_array($params)){
			$flat = self::flattenArray($params);
			foreach ($flat as $key => $value){
				$html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
			}
		}
		
		$html .= '</form>';
		$html .= '<script type="text/javascript">function postit(){ document.auth.submit(); }</script>';
		$html .= '</body></html>';
		echo $html;
	}
	
	/**
	 * Simple HTTP request with file_get_contents
	 * Provides basic HTTP calls.
	 * 
	 * @param $url string Full URL to load
	 * @param $options array Stream context options (http://php.net/stream-context-create)
	 * @param $responseHeaders string Response headers after HTTP call. Useful for error debugging.
	 * 
	 * @return string Content of destination, without headers
	 */
	protected static function httpRequest($url, $options = null, &$responseHeaders = null){
		$context = null;
		if (!empty($options) && is_array($options)){
			$context = stream_context_create($options);
		}
		
		$content = @file_get_contents($url, false, $context);
		$responseHeaders = implode("\r\n", $http_response_header);
		
		return $content;
	}
	
	/**
	* Recursively converts object into array
	* Basically get_object_vars, but recursive.
	* 
	* @param $obj Object
	* @return Array of object properties
	*/
	protected static function recursiveGetObjectVars($obj){
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val){
			$val = (is_array($val) || is_object($val)) ? self::recursiveGetObjectVars($val) : $val;
			$arr[$key] = $val;
		}
		
		return $arr;
	}

	/**
	 * Recursively converts multidimensional array into POST-friendly single dimensional array
	 * 
	 * @param $array array Array to be flatten
	 * @param $prefix string String to be prefixed to flatenned variable name
	 * @param $results array Existing array of flattened inputs to be merged upon
	 * 
	 * @return array A single dimensional array with POST-friendly name
	 */
	protected static function flattenArray($array, $prefix = null, $results = array()){
		//if (is_null($prefix)) $prefix = 'array';

		foreach ($array as $key => $val){
			$name = (empty($prefix)) ? $key : $prefix."[$key]";
			
			if (is_array($val)){
				$results = array_merge($results, self::flattenArray($val, $name));
			}
			else{
				$results[$name] = $val;
			}
		}
		
		return $results;
	}
}