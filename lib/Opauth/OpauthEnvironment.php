<?php
/**
 * Opauth Environment
 * Individual environment are to be extended from this class
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.Strategy
 * @license      MIT License
 */

/**
 * Opauth Environment
 * Individual environment are to be extended from this class
 *
 * @package            Opauth.Environment
 */
class OpauthEnvironment {

	/**
	 * Redirect to $url with HTTP header (Location: )
	 *
	 * @param string $url URL to redirect user to
	 * @param boolean $exit Whether to call exit() right after redirection
	 */
	public function redirect($url, $exit = true) {
		header("Location: $url");

		if ($exit) {
			exit();
		}
	}
}
