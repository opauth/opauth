<?php
class Facebook extends OpauthStrategy{
	
/**
 * Compulsory config keys, listed as unassociative arrays
 * eg. array('app_id', 'app_secret');
 */
	public $expects = array('app_id', 'app_secret');
	
/**
 * Optional config keys with respective default values, listed as associative arrays
 * eg. array('scope' => 'email');
 */
	public $defaults = array(
		'scope' => null
	);
	
	public function __construct(&$Opauth, $strategy){
		parent::__construct($Opauth, $strategy);
	}
	
/**
 * Auth request
 */
	public function request(){
		echo 'Request';
		exit();
		$url = 'https://www.facebook.com/dialog/oauth';
		
		$params = array(
			'client_id' => $this->strategy['app_id'],
			'redirect_uri' => $this->strategy['redirect_uri']
		);
		if (!empty($this->configs['scope'])) $params['scope'] = $this->configs['scope'];
		
		return $url.'?'.http_build_query($params);	
	}
}