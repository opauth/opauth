<?php
class Twitter extends OpauthStrategy{
	
/**
 * Compulsory config keys, listed as unassociative arrays
 * eg. array('app_id', 'app_secret');
 */
	public $expects = array('key', 'secret');
	
/**
 * Optional config keys with respective default values, listed as associative arrays
 * eg. array('scope' => 'email');
 */
	public $defaults = array(
		'oauth_callback' => '{complete_path}facebook/oauth_callback',
		'request_token_url' => 'https://api.twitter.com/oauth/request_token',
		'authorize_url' => 'https://api.twitter.com/oauth/authorize',
		'access_token_url' => 'https://api.twitter.com/oauth/access_token'
	);
	
	public function __construct(&$Opauth, $strategy){
		parent::__construct($Opauth, $strategy);
	}
	
/**
 * Auth request
 */
	public function request(){
		$params = array(
			'consumer_key' => $this->strategy['key'],
			'consumer_secret' => $this->strategy['secret'],
			'oauth_callback' => $this->strategy['oauth_callback'],
		);

		$data = http_build_query($params);
		$response = $this->httpRequest($this->strategy['request_token_url'], false, stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => 
					"Content-Length: ".strlen($data)."\r\n".
					"Authorization: OAuth".strlen($data)."\r\n".
				
				'content' => $data
			)	
		)));
		print_r($response);
		exit();
		//$response = $this->httpRequest($url.'?'.http_build_query($params));
		//$this->redirect($this->strategy['request_token_url'].'?'.http_build_query($params));
	}
	
}