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
 * Opauth StrategyInterface
 * Individual strategies should implement this interface
 *
 * @package			Opauth
 */
interface StrategyInterface {

	/**
	 * Handles the initial Oauth request
	 *
	 */
	public function request();

	/**
	 * Handles the callback from Oauth
	 *
	 * @return Response The Opauth Response object
	 */
	public function callback();

}
