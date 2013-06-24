<?php
/**
 * Opauth Strategy
 * Individual strategies are to be extended from this class
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.Strategy
 * @license      MIT License
 */

/**
 * Opauth Strategy
 * Individual strategies are to be extended from this class
 *
 * @package			Opauth.Strategy
 */
class OpauthStrategy {

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
	 * Configurations and settings unique to a particular strategy
	 */
	protected $strategy;

	/**
	 * Safe env values from Opauth, with critical parameters stripped out
	 */
	protected $env;

	/**
	 * Constructor
	 *
	 * @param array $strategy Strategy-specific configuration
	 * @param array $env Safe env values from Opauth, with critical parameters stripped out
	 */
	public function __construct($strategy, $env) {
		$this->strategy = $strategy;
		$this->env = $env;

		// Include some useful values from Opauth's env
		$this->strategy['strategy_callback_url'] = $this->env['host'].$this->env['callback_url'];

		if ($this->name === null) {
			$this->name = (isset($name) ? $name : get_class($this));
		}

		if (is_array($this->expects)) {
			foreach ($this->expects as $key){
				$this->expects($key);
			}
		}

		if (is_array($this->defaults)) {
			foreach ($this->defaults as $key => $value) {
				$this->optional($key, $value);
			}
		}

		/**
		 * Additional helpful values
		 */
		$this->strategy['path_to_strategy'] = $this->env['path'].$this->strategy['strategy_url_name'].'/';
		$this->strategy['complete_url_to_strategy'] = $this->env['host'].$this->strategy['path_to_strategy'];


		$dictionary = array_merge($this->env, $this->strategy);
		foreach ($this->strategy as $key=>$value) {
			$this->strategy[$key] = $this->envReplace($value, $dictionary);
		}
	}

	/**
	 * Auth request
	 * aka Log in or Register
	 */
	public function request() {
	}

	/**
	 * Packs $auth nicely and send to callback_url, ships $auth either via GET, POST or session.
	 * Set shipping transport via callback_transport config, default being session.
	 */
	public function callback() {
		$timestamp = date('c');

		// To standardize the way of accessing data, objects are translated to arrays
		$this->auth = $this->recursiveGetObjectVars($this->auth);

		$this->auth['provider'] = $this->strategy['strategy_name'];

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
	 * More info: https://github.com/uzyn/opauth/wiki/Auth-response#wiki-error-response
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
	public function errorCallback($error) {
		$timestamp = date('c');

		$error = $this->recursiveGetObjectVars($error);
		$error['provider'] = $this->strategy['strategy_name'];

		$params = array(
			'error' => $error,
			'timestamp' => $timestamp
		);

		$this->shipToCallback($params);
	}

	/**
	 * Send $data to callback_url using specified transport method
	 *
	 * @param array $data Data to be sent
	 * @param string $transport Callback method, either 'get', 'post' or 'session'
	 *        'session': Default. Works best unless callback_url is on a different domain than Opauth
	 *        'post': Works cross-domain, but relies on availability of client-side JavaScript.
	 *        'get': Works cross-domain, but may be limited or corrupted by browser URL length limit
	 *               (eg. IE8/IE9 has 2083-char limit)
	 *
	 */
	private function shipToCallback($data, $transport = null) {
		if (empty($transport)) {
			$transport = $this->env['callback_transport'];
		}

		switch($transport) {
			case 'get':
				$this->redirect($this->env['callback_url'].'?'.http_build_query(array('opauth' => base64_encode(serialize($data))), '', '&'));
				break;
			case 'post':
				$this->clientPost($this->env['callback_url'], array('opauth' => base64_encode(serialize($data))));
				break;
			case 'session':
			default:
				if(!session_id()) {
					session_start();
				}
				$_SESSION['opauth'] = $data;
				$this->redirect($this->env['callback_url']);
		}
	}

	/**
	 * Call an action from a defined strategy
	 *
	 * @param string $action Action name to call
	 * @param string $defaultAction If an action is not defined in a strategy, calls $defaultAction
	 */
	public function callAction($action, $defaultAction = 'request') {
		if (method_exists($this, $action)) return $this->{$action}();
		else return $this->{$defaultAction}();
	}

	/**
	 * Ensures that a compulsory value is set, throws an error if it's not set
	 *
	 * @param string $key Expected configuration key
	 * @param string $not If value is set as $not, trigger E_USER_ERROR
	 * @return mixed The loaded value
	 */
	protected function expects($key, $not = null) {
		if (!array_key_exists($key, $this->strategy)) {
			trigger_error($this->name." config parameter for \"$key\" expected.", E_USER_ERROR);
			exit();
		}

		$value = $this->strategy[$key];
		if (empty($value) || $value == $not) {
			trigger_error($this->name." config parameter for \"$key\" expected.", E_USER_ERROR);
			exit();
		}

		return $value;
	}

	/**
	 * Loads a default value into $strategy if the associated key is not found
	 *
	 * @param string $key Configuration key to be loaded
	 * @param string $default Default value for the configuration key if none is set by the user
	 * @return mixed The loaded value
	 */
	protected function optional($key, $default = null) {
		if (!array_key_exists($key, $this->strategy)) {
			$this->strategy[$key] = $default;
			return $default;
		}

		else return $this->strategy[$key];
	}

	/**
	 * Security: Sign $auth before redirecting to callback_url
	 *
	 * @param string $timestamp ISO 8601 formatted date
	 * @return string Resulting signature
	 */
	protected function sign($timestamp = null) {
		if (is_null($timestamp)) $timestamp = date('c');

		$input = sha1(print_r($this->auth, true));
		$hash = $this->hash($input, $timestamp, $this->env['security_iteration'], $this->env['security_salt']);

		return $hash;
	}

	/**
	 * Maps user profile to auth response
	 *
	 * @param array $profile User profile obtained from provider
	 * @param string $profile_path Path to a $profile property. Use dot(.) to separate levels.
	 *        eg. Path to $profile['a']['b']['c'] would be 'a.b.c'
	 * @param string $auth_path Path to $this->auth that is to be set.
	 */
	protected function mapProfile($profile, $profile_path, $auth_path) {
		$from = explode('.', $profile_path);

		$base = $profile;
		foreach ($from as $element) {
			if (is_array($base) && array_key_exists($element, $base)) {
				$base = $base[$element];
			} else {
				return false;
			}
		}
		$value = $base;

		$to = explode('.', $auth_path);

		$auth = &$this->auth;
		foreach ($to as $element) {
			$auth = &$auth[$element];
		}
		$auth = $value;
		return true;

	}


	/**
	 * *****************************************************
	 * Utilities
	 * A collection of static functions for strategy's use
	 * *****************************************************
	 */

	/**
	 * Static hashing function
	 *
	 * @param string $input Input string
	 * @param string $timestamp ISO 8601 formatted date
	 * @param int $iteration Number of hash iterations
	 * @param string $salt
	 * @return string Resulting hash
	 */
	public static function hash($input, $timestamp, $iteration, $salt) {
		$iteration = intval($iteration);
		if ($iteration <= 0) {
			return false;
		}

		for ($i = 0; $i < $iteration; ++$i) {
			$input = base_convert(sha1($input.$salt.$timestamp), 16, 36);
		}
		return $input;
	}

	/**
	 * Redirect to $url with HTTP header (Location: )
	 *
	 * @param string $url URL to redirect user to
	 * @param boolean $exit Whether to call exit() right after redirection
	 */
	public static function redirect($url, $exit = true) {
		header("Location: $url");
		if ($exit) {
			exit();
		}
	}

	/**
	 * Client-side GET: This function builds the full HTTP URL with parameters and redirects via Location header.
	 *
	 * @param string $url Destination URL
	 * @param array $data Data
	 * @param boolean $exit Whether to call exit() right after redirection
	 */
	public static function clientGet($url, $data = array(), $exit = true) {
		self::redirect($url.'?'.http_build_query($data, '', '&'), $exit);
	}

	/**
	 * Generates a simple HTML form with $data initialized and post results via JavaScript
	 *
	 * @param string $url URL to be POSTed
	 * @param array $data Data to be POSTed
	 */
	public static function clientPost($url, $data = array()) {
		$html = '<html><body onload="postit();"><form name="auth" method="post" action="'.$url.'">';

		if (!empty($data) && is_array($data)) {
			$flat = self::flattenArray($data);
			foreach ($flat as $key => $value) {
				$html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
			}
		}

		$html .= '</form>';
		$html .= '<script type="text/javascript">function postit(){ document.auth.submit(); }</script>';
		$html .= '</body></html>';
		echo $html;
	}

	/**
	 * Basic server-side HTTP GET request via self::httpRequest(), wrapper of file_get_contents
	 *
	 * @param string $url Destination URL
	 * @param array $data Data to be submitted via GET
	 * @param array $options Additional stream context options, if any
	 * @param string $responseHeaders Response headers after HTTP call. Useful for error debugging.
	 * @return string Content resulted from request, without headers
	 */
	public static function serverGet($url, $data, $options = null, &$responseHeaders = null) {
		return self::httpRequest($url.'?'.http_build_query($data, '', '&'), $options, $responseHeaders);
	}

	/**
	 * Basic server-side HTTP POST request via self::httpRequest(), wrapper of file_get_contents
	 *
	 * @param string $url Destination URL
	 * @param array $data Data to be POSTed
	 * @param array $options Additional stream context options, if any
	 * @param string $responseHeaders Response headers after HTTP call. Useful for error debugging.
	 * @return string Content resulted from request, without headers
	 */
	public static function serverPost($url, $data, $options = array(), &$responseHeaders = null) {
		if (!is_array($options)) {
			$options = array();
		}

		$query = http_build_query($data, '', '&');

		$stream = array('http' => array(
			'method' => 'POST',
			'header' => "Content-type: application/x-www-form-urlencoded",
			'content' => $query
		));

		$stream = self::arrayReplaceRecursive($stream, $options);

		return self::httpRequest($url, $stream, $responseHeaders);
	}

	/**
	 * Simple server-side HTTP request with file_get_contents
	 * Provides basic HTTP calls.
	 * See serverGet() and serverPost() for wrapper functions of httpRequest()
	 *
	 * Notes:
	 * Reluctant to use any more advanced transport like cURL for the time being to not
	 *     having to set cURL as being a requirement.
	 * Strategy is to provide own HTTP transport handler if requiring more advanced support.
	 *
	 * @param string $url Full URL to load
	 * @param array $options Stream context options (http://php.net/stream-context-create)
	 * @param string $responseHeaders Response headers after HTTP call. Useful for error debugging.
	 * @return string Content resulted from request, without headers
	 */
	public static function httpRequest($url, $options = null, &$responseHeaders = null) {
		$context = null;
		if (!empty($options) && is_array($options)) {
			if (empty($options['http']['header'])) {
				$options['http']['header'] = "User-Agent: opauth";
			} else {
				$options['http']['header'] .= "\r\nUser-Agent: opauth";
			}
		} else {
			$options = array('http' => array('header' => 'User-Agent: opauth'));
		}
		$context = stream_context_create($options);

		$content = file_get_contents($url, false, $context);
		$responseHeaders = implode("\r\n", $http_response_header);

		return $content;
	}

	/**
	* Recursively converts object into array
	* Basically get_object_vars, but recursive.
	*
	* @param mixed $obj Object
	* @return array Array of object properties
	*/
	public static function recursiveGetObjectVars($obj) {
		$arr = array();
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;

		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? self::recursiveGetObjectVars($val) : $val;

			// Transform boolean into 1 or 0 to make it safe across all Opauth HTTP transports
			if (is_bool($val)) $val = ($val) ? 1 : 0;

			$arr[$key] = $val;
		}

		return $arr;
	}

	/**
	 * Recursively converts multidimensional array into POST-friendly single dimensional array
	 *
	 * @param array $array Array to be flatten
	 * @param string $prefix String to be prefixed to flattened variable name
	 * @param array $results Existing array of flattened inputs to be merged upon
	 *
	 * @return array A single dimensional array with POST-friendly name
	 */
	public static function flattenArray($array, $prefix = null, $results = array()) {
		foreach ($array as $key => $val) {
			$name = (empty($prefix)) ? $key : $prefix."[$key]";

			if (is_array($val)) {
				$results = array_merge($results, self::flattenArray($val, $name));
			} else {
				$results[$name] = $val;
			}
		}

		return $results;
	}

	/**
	 * Replace defined env values enclosed in {} with values from $dictionary
	 *
	 * @param string $value Input string
	 * @param array $dictionary Dictionary to lookup values from
	 * @return string String substituted with value from dictionary, if applicable
	 */
	public static function envReplace($value, $dictionary) {
		if (is_string($value) && preg_match_all('/{([A-Za-z0-9-_]+)}/', $value, $matches)) {
			foreach ($matches[1] as $key) {
				if (array_key_exists($key, $dictionary)) {
					$value = str_replace('{'.$key.'}', $dictionary[$key], $value);
				}
			}
			return $value;
		}
		return $value;
	}

	/**
	 * array_replace_recursive() polyfill for PHP 5.2
	 * From: http://sg.php.net/manual/en/function.array-replace-recursive.php#92574
	 *
	 * @param array $array The array in which elements are replaced.
	 * @param array $array1 The array from which elements will be extracted
	 * @return array Returns an array or null if an error occurs.
	 */
	public static function arrayReplaceRecursive($array, $array1) {

		if (!function_exists('array_replace_recursive')) {
			function array_replace_recursive($array, $array1) {
				function recurse($array, $array1) {
					foreach ($array1 as $key => $value) {

						if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
							$array[$key] = array();
						}

						if (is_array($value)) {
							$value = recurse($array[$key], $value);
						}

						$array[$key] = $value;
		      		}
					return $array;
				}

				$args = func_get_args();

				$array = $args[0];

				if (!is_array($array)) {
					return $array;
				}

				for ($i = 1; $i < count($args); $i++) {
					if (is_array($args[$i])) {
						$array = recurse($array, $args[$i]);
					}
				}

				return $array;
		  	}
		}

		return array_replace_recursive($array, $array1);
	}
}
