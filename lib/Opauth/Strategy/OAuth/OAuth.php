<?php
class OAuth extends OpauthStrategy{
	
/**
 * Compulsory configuration options
 */
	public $expects = array(
		'consumer_key', 		
		'consumer_secret',
									// Example: for Twitter
		'request_token_url',		// 'https://api.twitter.com/oauth/request_token'
		'access_token_url'			// 'https://api.twitter.com/oauth/authorize' or 'https://api.twitter.com/oauth/authenticate'
	);
	
/**
 * Compulsory configuration options
 */
	public $defaults = array(
		'method' => 'POST', 		// The HTTP method being used. e.g. POST, GET, HEAD etc 
		'oauth_callback' => '{complete_path}oauth/oauth_callback',
		
		// From tmhOAuth
		// Refer to Vendor/tmhOAuth/tmhOAuth.php for details on these
		'user_token'					=> '',
		'user_secret'					=> '',
		'use_ssl'						=> true,
		'debug'							=> false,
		'force_nonce'					=> false,
		'nonce'							=> false, // used for checking signatures. leave as false for auto
		'force_timestamp'				=> false,
		'timestamp'						=> false, // used for checking signatures. leave as false for auto
		'oauth_version'					=> '1.0',
		'curl_connecttimeout'			=> 30,
		'curl_timeout'					=> 10,
		'curl_ssl_verifypeer'			=> false,
		'curl_followlocation'			=> false, // whether to follow redirects or not
		'curl_proxy'					=> false, // really you don't want to use this if you are using streaming
		'curl_proxyuserpwd'				=> false, // format username:password for proxy, if required
		'is_streaming'					=> false,
		'streaming_eol'					=> "\r\n",
		'streaming_metrics_interval'	=> 60,
		'as_header'				  		=> true,
	);
	
/**
 * tmhOAuth instance
 */
	private $tmhOAuth;
	
	public function __construct(&$Opauth, $strategy){
		parent::__construct($Opauth, $strategy);
		
		require dirname(__FILE__).'/Vendor/tmhOAuth/tmhOAuth.php';
		$this->tmhOAuth = new tmhOAuth($this->strategy);
	}
	
/**
 * Auth request
 */
	public function request(){
		$params = array(
			'oauth_callback' => $this->strategy['oauth_callback']
		);

		$results =  $this->_request('POST', $this->strategy['request_token_url'], $params);
		
		if ($results !== false && !empty($results['oauth_token']) && !empty($results['oauth_token_secret'])){
			session_start();
			$_SESSION['_opauth_oauth'] = $results;
			
			$this->_access_token($results['oauth_token']);
		}
	}

/**
 * Receives oauth_verifier, requests for access_token and redirect to callback
 */
	public function oauth_callback(){
		session_start();
		$this->auth = array(
			'provider' => 'oauth',
			'uid' => null,
			'credentials' => array_merge($_SESSION['_opauth_oauth'], $_REQUEST)
		);
		
		unset($_SESSION['_oauth_oauth']);
		
		$this->callback();
	}
	
	private function _access_token($oauth_token){
		$params = array(
			'oauth_token' => $oauth_token
		);
		
		$this->redirect($this->strategy['access_token_url'].'?'.http_build_query($params));
	}
	
/**
 * Wrapper of tmhOAuth's request() with Opauth's error handling.
 * 
 * request():
 * Make an HTTP request using this library. This method doesn't return anything.
 * Instead the response should be inspected directly.
 *
 * @param string $method the HTTP method being used. e.g. POST, GET, HEAD etc
 * @param string $url the request URL without query string parameters
 * @param array $params the request parameters as an array of key=value pairs
 * @param string $useauth whether to use authentication when making the request. Default true.
 * @param string $multipart whether this request contains multipart data. Default false
 */	
	private function _request($method, $url, $params = array(), $useauth = true, $multipart = false){
		$code = $this->tmhOAuth->request($method, $url, $params, $useauth, $multipart);

		if ($code == 200){
			$response = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
			return $response;		
		}
		else {
			// Log error
			//$this->log($this->tmhOAuth->response['response']);
			print_r($code);
			print_r($this->tmhOAuth->response['response']);
			return false;
		}
		
		
	}
	
}