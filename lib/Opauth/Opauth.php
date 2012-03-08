<?php
/**
 * Opauth core
 *
 */
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
		
		if (!empty($this->env['strategy'])){
			if (array_search($this->env['strategy'], $this->configs['strategies'])){
				require 'OpauthStrategy.php'; 
			}
			debug('Error - Invalid strategy.');
		}
	}
	
	/**
	 * Parses Request URI
	 */
	protected function _parseUri(){
		$this->env['request'] = substr($this->configs['uri'], strlen($this->configs['path']) - 1);
		
		if (preg_match_all('/\/([A-Za-z0-9-_]+)/', $this->env['request'], $matches)){
			foreach ($matches[1] as $match){
				$this->env['params'][] = $match;
			}
		}
		
		if (!empty($this->env['params'][0])) $this->env['strategy'] = $this->env['params'][0];
	}
}