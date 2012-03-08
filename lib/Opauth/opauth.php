<?php
class Opauth{
	public $configs;
	
	public function __construct($configs = array()){
		echo 'Welcome to Opauth';
		
		$this->configs = array_merge(array(
			'uri' => $_SERVER['REQUEST_URI'],
			'path' => '/'
		), $configs);
		
		$this->_parseUri();
	}
	
	/**
	 * Parses Request URI
	 */
	protected function _parseUri(){
		
		echo $this->configs['uri'];
	}
}