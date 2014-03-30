<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth;

use \ArrayAccess;

/**
 * Opauth Response
 * Individual strategies should return this in their callback() method
 *
 */
class Response implements ArrayAccess
{

    /**
     * Provider name
     *
     * @var string
     */
    public $provider;

    /**
     * Raw response data
     *
     * @var mixed
     */
    public $raw;

    /**
     * User unique identifier
     *
     * @var mixed
     */
    public $uid;

    /**
     * User name
     *
     * @var string
     */
    public $name;

    /**
     * User credentials
     *
     * @var array
     */
    public $credentials;

    /**
     * Info
     * @var array
     */
    public $info = array();

    /**
     * Key => value pairs to map raw data
     *
     * Key contains dot formatted path to Response attributes,
     * values contain path to raw data to read from.
     *
     * Strategies can define a response map, or users can add 'responseMap' to
     * the config array, to override and define the way they like the response
     * data to be formatted
     *
     * Example:
     *    protected $responseMap = array(
     *        'uid' => 'id',
     *        'name' => 'name',
     *        'info.name' => 'name',
     *        'info.nickname' => 'screen_name',
     *        'info.location' => 'location',
     *        'info.description' => 'description',
     *        'info.image' => 'profile_image_url',
     *        'info.urls.website' => 'url'
     *    );
     *
     * @var array
     */
    protected $map = array();

    /**
     * Constructor
     *
     * @param string $provider Use $this->strategy['provider'] so aliassed strategies are handled correct
     * @param array $raw Raw response data from provider
     */
    public function __construct($provider, $raw)
    {
        $this->provider = $provider;
        $this->raw = $raw;
    }

    /**
     * Checks if required parameters are set
     *
     * @return boolean
     */
    public function isValid()
    {
        $attributes = array('provider', 'raw', 'uid', 'name', 'credentials');
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
     * - setData('info.nickname', 'screen_name') sets value from $response->raw['screen_name']
     *   to $response->info['nickname']
     * - setData('uid', 'nested.user_id') sets value from $response->raw['nested']['userid'] to $response->uid
     *
     * @param string $path Path to property. eg 'info.nickname' sets to $info['nickname']
     * @param string $rawPath Path to a $raw data. eg 'screen_name' reads from $raw['screen_name']
     * @return boolean
     */
    public function setData($path, $rawPath)
    {
        $rawValue = $this->getRaw($rawPath);
        if (!$rawValue) {
            return false;
        }
        return $this->mergeValue($path, $rawValue);
    }

    /**
     * Set data map, array of 'path' => 'rawPath' key/value pairs
     * See also setData()
     *
     * @param array $map
     */
    public function setMap($map = array())
    {
        $this->map = $map;
    }

    /**
     * Getter for the response map
     *
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Sets attribute data based on data mapping
     * Use setMap() to set the data mapping
     */
    public function map()
    {
        $map = $this->getMap();
        foreach ($map as $path => $rawPath) {
            $this->setData($path, $rawPath);
        }
        $this->setMap();
    }

    /**
     * Gets the raw data value through path
     *
     * @param string $path see setData()
     * @return string Value from raw data or null if not found in path
     */
    protected function getRaw($path)
    {
        if (strpos($path, '.') === false) {
            return isset($this->raw[$path]) ? $this->raw[$path] : null;
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
     * @param string $path path to Property see setData()
     * @param mixed $value value to set
     * @return boolean
     */
    protected function mergeValue($path, $value)
    {
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
        $this->{$attribute} = array_merge_recursive($this->{$attribute}, $value);
        return true;
    }

    /**
     * Array access read implementation, magic getter for raw data
     *
     * @param string $name Name of the key being accessed.
     * @return mixed
     */
    public function offsetGet($name)
    {
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
     */
    public function offsetSet($name, $value)
    {
        $this->{$name} = $value;
    }

    /**
     * Array access isset() implementation
     *
     * @param string $name thing to check.
     * @return boolean
     */
    public function offsetExists($name)
    {
        return isset($this->{$name});
    }

    /**
     * Array access unset() implementation
     *
     * @param string $name Name to unset.
     */
    public function offsetUnset($name)
    {
        unset($this->{$name});
    }

    /**
     * Magic getter for raw data, like arrayaccess
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * Magic method for `isset()`
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }
}
