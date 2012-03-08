<?php
class Opauth{
	public function __construct($uri = null){
		echo 'Welcome to Opauth';
		
		if (!is_null($uri)) $this->_uri($uri);
	}
	
	/**
	 * Parses Request URI
	 */
	private function _uri($uri = null){
		if (is_null($uri)) $uri = $_SERVER['REQUEST_URI'];
		
		echo $uri;
	}
}