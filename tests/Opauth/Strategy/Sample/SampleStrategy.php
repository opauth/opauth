<?php
/**
 * Sample strategy for Opauth unit testing
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @copyright		Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link 			http://opauth.org
 * @license			MIT License
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
}