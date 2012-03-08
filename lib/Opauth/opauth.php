<?php
class Opauth{
	/**
	 * User configuraable settings
	 */
	public $configs;	
	
	/**
	 * Environment variables
	 */
	public $env;
	
	public function __construct($configs = array()){
		echo 'Welcome to Opauth';
		
		$this->configs = array_merge(array(
			'uri' => $_SERVER['REQUEST_URI'],
			'path' => '/',
			'debug' => false
		), $configs);
		
		if ($this->configs['debug']) require('debug.php');
		
		$this->_parseUri();
	}
	
	/**
	 * Parses Request URI
	 */
	protected function _parseUri(){
		$this->env['request'] = substr($this->configs['uri'], strlen($this->configs['path']) - 1);
		
	}
}