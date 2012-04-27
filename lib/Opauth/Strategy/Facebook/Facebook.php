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
		'redirect_uri' => '{complete_path}facebook/int_callback'
	);
	
	public function __construct(&$Opauth, $strategy){
		parent::__construct($Opauth, $strategy);
	}
	
/**
 * Auth request
 */
	public function request(){
		$url = 'https://www.facebook.com/dialog/oauth';
		$params = array(
			'client_id' => $this->strategy['app_id'],
			'redirect_uri' => $this->strategy['redirect_uri']
		);
		if (!empty($this->strategy['scope'])) $params['scope'] = $this->strategy['scope'];
		if (!empty($this->strategy['state'])) $params['state'] = $this->strategy['state'];
		if (!empty($this->strategy['response_type'])) $params['response_type'] = $this->strategy['response_type'];
		if (!empty($this->strategy['display'])) $params['display'] = $this->strategy['display'];
		
		$this->redirect($url.'?'.http_build_query($params));
	}
	
/**
 * Internal callback, after Facebook's OAuth
 */
	public function int_callback(){
		if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
			$url = 'https://graph.facebook.com/oauth/access_token';
			$params = array(
				'client_id' =>$this->strategy['app_id'],
				'client_secret' => $this->strategy['app_secret'],
				'redirect_uri'=> $this->strategy['redirect_uri'],
				'code' => trim($_GET['code'])
			);
			
			$response = $this->httpRequest($url.'?'.http_build_query($params));
			
			parse_str($response, $results);
			if (!empty($results) && !empty($results['access_token'])){
				$me = $this->me($results['access_token']);
				print_r($me);
			}
		}
		else{
			// Error or authentication declined
		}
	}
	
/**
 * Queries Facebook Graph API for user info
 *
 * @param string $access_token 
 * @return array Parsed JSON results
 */
	public function me($access_token){
		$me = $this->httpRequest('https://graph.facebook.com/me?access_token='.$access_token);
		if (!empty($me)){
			return json_decode($me);
		}
	}
}