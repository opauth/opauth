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
namespace Opauth\Strategy\Sample;
use Opauth\AbstractStrategy;
use Opauth\Response;

class Strategy extends AbstractStrategy {

	/**
	 * Compulsory config keys, listed as unassociative arrays
	 * eg. array('app_id', 'app_secret');
	 */
	public $expects = array('sample_id', 'sample_secret');

	/**
	 * Optional config keys, without predefining any default values.
	 */
	public $optionals = array('scope', 'state', 'access_type', 'approval_prompt');

	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 * eg. array('scope' => 'email');
	 */
	public $defaults = array(
		'scope' => 'test_scope'
	);

	/**
	 * For testing responses
	 *
	 * @var type
	 */
	public $testRaw = array('some' => 'raw data');

	public $testMap = array();



	/**
	 * An arbitrary function
	 */
	public function request() {
		echo 'request() called';
	}

	/**
	 * An arbritary function
	 */
	public function callback() {
		$response = new Response($this->strategy['provider'], $this->testRaw);
		$response->setMap($this->testMap);
		return $response;
	}

	/**
	 * overriding to change visbility to public
	 */
	public function callbackUrl() {
		return parent::callbackUrl();
	}

	/**
	 * overriding to change visbility to public
	 */
	public function sessionData($data = null) {
		return parent::sessionData($data);
	}

	/**
	 * overriding to change visbility to public
	 */
	public function addParams($configKeys, $params = array()) {
		return parent::addParams($configKeys, $params);
	}
}