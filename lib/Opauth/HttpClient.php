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
use Opauth\Transport\TransportInterface;

/**
 * Opauth HttpClient
 * Http client wrapper class, uses Transport classes to handle http requests
 *
 * @package      Opauth
 */
class HttpClient {

	protected static $transport;

	public static function transport(TransportInterface $transport = null) {
		if ($transport) {
			static::$transport = $transport;
		}
		return static::$transport;
	}

	public static function redirect($url, $data = array(), $exit = true) {
		return static::transport()->redirect($url, $data, $exit);
	}

	public static function get($url, $data = array()) {
		return static::transport()->get($url, $data);
	}

	public static function post($url, $data) {
		return static::transport()->post($url, $data);
	}

}