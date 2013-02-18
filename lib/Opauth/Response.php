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
use \ArrayAccess;

/**
 * Opauth Response
 * Individual strategies should return this in their callback() method
 *
 * @package      Opauth
 */
class Response implements ArrayAccess {

	public $provider;

	public $raw;

	public $uid;

	public $name;

	public $credentials;

	public $info = array();

	protected $map = array();

	/**
	 *
	 * @param string $provider
	 * @param integer $uid
	 * @param array $raw
	 */
	public function __construct($provider, $raw) {
		$this->provider = $provider;
		$this->raw = $raw;
	}

	/**
	 *  Checks if required parameters are set
	 * @return boolean
	 */
	public function isValid() {
		$attributes = array('provider', 'uid', 'name');
		foreach ($attributes as $attribute) {
			if (empty($this->{$attribute})) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Copies raw data to other attribute.
	 * Uses paths to data, Use dot(.) to separate levels
	 * Examples:
	 * - Path to $response->info['a']['b']['c'] would be 'info.a.b.c'
	 * - setData('screen_name', 'info.nickname') sets value from $response->raw['screen_name'] to $response->info['nickname']
	 * - setData('nested.user_id', 'uid') sets value from $response->raw['nested']['userid'] to $response->uid
	 *
	 * @param string $rawPath Path to a $raw data. eg 'screen_name' reads from $raw['screen_name']
	 * @param string $path Path to property. eg 'info.nickname' sets to $info['nickname']
	 */
	public function setData($rawPath, $path) {
		$rawValue = $this->getRaw($rawPath);
		if (!$rawValue) {
			return false;
		}
		return $this->mergeValue($path, $rawValue);
	}

	/**
	 *
	 * @param array $map
	 */
	public function setMap($map) {
		$this->map = $map;
	}

	/**
	 *
	 * @return array
	 */
	public function getMap() {
		return $this->map;
	}

	/**
	 *
	 * @param array $map
	 */
	public function map($map = array()) {
		if (empty($map)) {
			$map = $this->getMap();
		}
		foreach ($map as $rawPath => $path) {
			$this->setData($rawPath, $path);
		}
	}

	/**
	 * Gets the raw data value through path
	 *
	 * @param type $path see setData()
	 * @return string Value from raw data or null if not found in path
	 */
	protected function getRaw($path) {
		if (strpos($path, '.') === false) {
			return $this->raw[$path];
		}
		$keys = explode('.', $path);
		$value = $this->raw;
		foreach ($keys as $key) {
			if (!isset($value[$key])) {
				return null;
			}
			$value = $value[$key];
		}
		return $value;
	}

	/**
	 * Merges a value into a property
	 *
	 * @param type $path path to Property see setData()
	 * @param type $value value to set
	 * @return boolean
	 */
	protected function mergeValue($path, $value) {
		$keys = explode('.', $path);
		krsort($keys);
		$attribute = array_pop($keys);
		if (!in_array($attribute, array_keys(get_class_vars(get_class())))) {
			return false;
		}
		foreach ($keys as $key) {
			$value = array($key => $value);
		}
		if (!is_array($value)) {
			$this->{$attribute} = $value;
			return true;
		}
		$this->{$attribute} = array_merge_recursive($this->$attribute, $value);
		return true;
	}

	/**
	 * Array access read implementation, magic getter for raw data
	 *
	 * @param string $name Name of the key being accessed.
	 * @return mixed
	 */
	public function offsetGet($name) {
		if (in_array($name, array('provider', 'raw', 'uid', 'name', 'info', 'credentials'))) {
			return $this->{$name};
		}
		if (isset($this->raw[$name])) {
			return $this->raw[$name];
		}
		return null;
	}

	/**
	 * Array access write implementation
	 *
	 * @param string $name Name of the key being written
	 * @param mixed $value The value being written.
	 * @return void
	 */
	public function offsetSet($name, $value) {
		$this->{$name} = $value;
	}

	/**
	 * Array access isset() implementation
	 *
	 * @param string $name thing to check.
	 * @return boolean
	 */
	public function offsetExists($name) {
		return isset($this->{$name});
	}

	/**
	 * Array access unset() implementation
	 *
	 * @param string $name Name to unset.
	 * @return void
	 */
	public function offsetUnset($name) {
		unset($this->{$name});
	}

	/**
	 * Magic getter for raw data, like arrayaccess
	 * @param type $name
	 * @return type
	 */
	public function __get($name) {
		return $this->offsetGet($name);
	}

	/**
	 *
	 * @param type $name
	 * @return type
	 */
	public function __isset($name) {
		return $this->offsetExists($name);
	}

}