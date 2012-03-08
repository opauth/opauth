<?php
/**
 * Opauth core
 *
 */
class Opauth{
	/**
	 * User configuraable settings
	 * - Do not refer to this anywhere in logic, except in __construct() of Opauth
	 */
	public $configs;	
	
	/**
	 * Environment variables
	 */
	public $env;
	
	/** 
	 * Defined strategies
	 */
	public $strategies;
	
	public function __construct($configs = array()){
		
		/* Setup */
		$this->configs = array_merge(array(
			'uri' => $_SERVER['REQUEST_URI'],
			'path' => '/',
			'debug' => false
		), $configs);
		
		$this->env = array(
			'LIB' => dirname(__FILE__).'/',
			'debug' => $this->configs['debug']
		);
		
		$this->strategies = $this->configs['strategies'];
		
		/* Run */
		$this->_parseUri();
		
		if (!empty($this->env['strategy'])){
			if (array_search($this->env['strategy'], $this->strategies)){
				require 'OpauthStrategy.php'; 
			}
			echo 'Error - Invalid strategy.';
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