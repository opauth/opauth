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

interface TransportInterface {

	public function redirect($url, $data = array(), $exit = true);

	public function get($url, $data = array());

	public function post($url, $data);

}