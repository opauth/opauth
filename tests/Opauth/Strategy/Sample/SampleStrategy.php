<?php
/**
 * Sample strategy for Opauth unit testing
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.OpauthTest.SampleStrategy
 * @license      MIT License
 */

class SampleStrategy extends OpauthStrategy{
	
	/**
	 * Compulsory config keys, listed as unassociative arrays
	 * eg. array('app_id', 'app_secret');
	 */
	public $expects = array('sample_id', 'sample_secret');
	
	/**
	 * Optional config keys, without predefining any default values.
	 */
	public $optionals = array('redirect_uri', 'scope', 'state', 'access_type', 'approval_prompt');
	
	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 * eg. array('scope' => 'email');
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}int_callback',
		'scope' => 'test_scope'
	);

	/**
	 * Auth request
	 */
	public function request(){
		echo 'request() called';
	}
	
	/**
	 * An arbritary function
	 */
	public function arbritary(){
		echo 'arbritary() called';
	}
	
	/**
	 * Successful auth, returns a normal auth response
	 */
	public function success(){
		$this->auth = array(
			'provider' => 'Sample',
			'uid' => 1564894354651654,
			'info' => array(
				'name' => 'John Doe',
				'email' => 'john@doe.com',
				'nickname' => 'john',
				'first_name' => 'John',
				'last_name' => 'Doe',
				'location' => 'Singapore, Singapore',
				'description' => 'I am the default human',
				'image' => 'http://example.org/sample.jpg',
				'phone' => '+65 9000 1000 (ext: 80)',
				'urls' => array(
					'website' => 'http://john.doe.com'
				)
			),
			'credentials' => array(
				'token' => 'g2L]G3^p1yS%83R|N3)Wt;??(L{E%Ff3Y[BfG9gSmw#EM]z~Jl`A~5AMoM({Y{_',
				'secret' => 'Wl3dwG15nUxmZ0UbRLwyP6IRyeYvvPUtUaxLxI6N07TQPgJ4lIVACQ9Ia1efT89'
			),
			'raw' => null
		);
		
		$this->callback();
	}
}