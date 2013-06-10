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

use Opauth\StrategyInterface;

/**
 * Opauth Strategy
 * Individual strategies are to be extended from this class
 *
 * @package			Opauth
 */
abstract class AbstractStrategy implements StrategyInterface {

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
	 * Configurations and settings unique to a particular strategy
	 */
	protected $strategy;

	/**
	 * Key for $_SESSION data
	 *
	 * @var string
	 */
	protected $sessionKey = '_opauth_data';

	/**
	 * Map response from raw data
	 *
	 * @var array
	 */
	protected $responseMap = array();

	/**
	 *
	 * @var Callback url which is called after request
	 */
	protected $callbackUrl;

	/**
	 * Http client transport class
	 *
	 * @var Opauth\Transport\TransportInterface
	 */
	protected $http;

	/**
	 * Constructor
	 *
	 * @param array $config Strategy-specific configuration
	 */
	public function __construct($config = array()) {
		$this->strategy = $config;
		$this->responseMap = $this->addParams(array('responseMap'), $this->responseMap);

		if (is_array($this->expects)) {
			foreach ($this->expects as $key) {
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
		foreach ($this->strategy as $key => $value) {
			$this->strategy[$key] = $this->envReplace($value, $this->strategy);
		}
	}

	/**
	 * Getter/setter for the complete callbackurl
	 *
	 * @return string
	 */
	public function callbackUrl($url = null) {
		if ($url) {
			$this->callbackUrl = $url;
		}
		return $this->callbackUrl;
	}

	/**
	 * Set transport class
	 *
	 * @param \Opautth\Transport\TransportInterface $transport
	 */
	public function setTransport(\Opauth\TransportInterface $transport) {
		$this->http = $transport;
	}

	/**
	 * Getter/setter method for session data
	 *
	 * @param array $data Array to write or null to read
	 * @return array Sessiondata
	 */
	protected function sessionData($data = null) {
		if (!session_id()) {
			session_start();
		}
		$key = $this->sessionKey . $this->strategy['provider'];
		if (!$data) {
			$data = $_SESSION[$key];
			unset($_SESSION[$key]);
			return $data;
		}
		return $_SESSION[$key] = $data;
	}

	/**
	 * Adds strategy config values to params array if set.
	 * $configKeys array may contain string values, or key => value pairs
	 * key for the strategy key, value for the params key to set
	 *
	 * @param array $configKeys
	 * @param array $params
	 * @return array
	 */
	protected function addParams($configKeys, $params = array()) {
		foreach ($configKeys as $configKey => $paramKey) {
			if (is_numeric($configKey)) {
				$configKey = $paramKey;
			}
			if (isset($this->strategy[$configKey])) {
				$params[$paramKey] = $this->strategy[$configKey];
			}
		}
		return $params;
	}

	/**
	 * Response callback
	 *
	 * More info: https://github.com/uzyn/opauth/wiki/Auth-response#wiki-error-response
	 *
	 * @param $raw Raw response from Oauth provider
	 * @param array $error Data on error to be sent back along with the callback
	 *	$error = array(
	 *		'code'		// Error code, can be int (HTTP status) or string (eg. access_denied)
	 *		'message'	// User-friendly error message
	 *	)
	 * @return Opauth\Response
	 */
	public function response($raw, $error = array()) {
		$response = new Response($this->strategy['provider'], $raw);
		$response->setMap($this->responseMap);
		if ($error) {
			$response->setError($error);
		}
		return $response;
	}

	/**
	 * Ensures that a compulsory value is set, throws an exception if it's not set
	 *
	 * @param string $key Expected configuration key
	 * @param string $not If value is set as $not, throw exception
	 * @return mixed The loaded value
	 */
	protected function expects($key, $not = null) {
		if (!array_key_exists($key, $this->strategy)) {
			throw new \Exception(get_class($this) . " config parameter for \"$key\" expected.");
		}

		$value = $this->strategy[$key];
		if (empty($value) || $value == $not) {
			throw new \Exception(get_class($this) . " config parameter for \"$key\" expected.");
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
		return $this->strategy[$key];
	}

	/**
	 * *****************************************************
	 * Utilities
	 * A collection of static functions for strategy's use
	 * *****************************************************
	 */

	/**
	* Recursively converts object into array
	* Basically get_object_vars, but recursive.
	*
	* @param mixed $obj Object
	* @return array Array of object properties
	*/
	public static function recursiveGetObjectVars($obj){
		$arr = array();
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;

		foreach ($_arr as $key => $val){
			$val = (is_array($val) || is_object($val)) ? self::recursiveGetObjectVars($val) : $val;

			// Transform boolean into 1 or 0 to make it safe across all Opauth HTTP transports
			if (is_bool($val)) $val = ($val) ? 1 : 0;

			$arr[$key] = $val;
		}

		return $arr;
	}

	/**
	 * Replace defined env values enclused in {} with values from $dictionary
	 *
	 * @param string $value Input string
	 * @param array $dictionary Dictionary to lookup values from
	 * @return string String substitued with value from dictionary, if applicable
	 */
	public static function envReplace($value, $dictionary) {
		if (is_string($value) && preg_match_all('/{([A-Za-z0-9-_]+)}/', $value, $matches)) {
			foreach ($matches[1] as $key){
				if (array_key_exists($key, $dictionary)){
					$value = str_replace('{'.$key.'}', $dictionary[$key], $value);
				}
			}
			return $value;
		}
		return $value;
	}

}
