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
				
				$this->auth = array(
					'provider' => 'facebook',
					'uid' => $me->id,
					'info' => array(
						'name' => $me->name,
						'image' => 'https://graph.facebook.com/'.$me->id.'/picture?type=square'
					),
					'credentials' => array(
						'token' => $results['access_token'],
						'expires' => date('c', time() + $results['expires'])
					),
					'raw' => $me
				);
				
				if (!empty($me->email)) $this->auth['info']['email'] = $me->email;
				if (!empty($me->username)) $this->auth['info']['nickname'] = $me->username;
				if (!empty($me->first_name)) $this->auth['info']['first_name'] = $me->first_name;
				if (!empty($me->last_name)) $this->auth['info']['last_name'] = $me->last_name;
				if (!empty($me->location)) $this->auth['info']['location'] = $me->location->name;
				if (!empty($me->location)) $this->auth['info']['location'] = $me->location->name;
				if (!empty($me->link)) $this->auth['info']['urls']['facebook'] = $me->link;
				if (!empty($me->website)) $this->auth['info']['urls']['website'] = $me->website;
				
				/**
				 * Missing optional info values
				 * - description
				 * - phone: not accessible via Facebook Graph API
				 */
				
				$this->callback();
			}
		}
		else{
			// Error or authentication declined
			// TODO: error handling
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