<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2013 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth;

/**
 * Transport Interface
 * Http transport adapters should implement this
 *
 * @package			Opauth
 */
interface TransportInterface {

	/**
	 * Redirect method
	 *
	 * @param string $url
	 * @param array $data
	 * @param boolean $exit
	 */
	public function redirect($url, $data = array(), $exit = true);

	/**
	 * Handles GET requests
	 *
	 * @param string $url
	 * @param array $data
	 */
	public function get($url, $data = array());

	/**
	 * Handles POST requests
	 *
	 * @param string $url
	 * @param array $data
	 */
	public function post($url, $data);

}