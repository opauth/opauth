<?php
/**
 * OpauthStrategyTest
 *
 * @copyright	Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link		 http://opauth.org
 * @package	  Opauth.OpauthStrategyTest
 * @license	  MIT License
 */

require './lib/Opauth/OpauthStrategy.php';
require './tests/Opauth/OpauthTest.php';

/**
 * OpauthTest class
 */
class OpauthStrategyTest extends OpauthTest{
	
	public function testHash(){
		$input = 'random string';
		$timestamp = date('c');
		$iteration = 250;
		$salt = 'sodium chrloride';
		$control = OpauthStrategy::hash($input, $timestamp, $iteration, $salt);
		$this->assertFalse(empty($control));
		
		// Ensure iteration is taken into account and producing different hash
		$diffIteration = OpauthStrategy::hash($input, $timestamp, 888, $salt);
		$this->assertFalse(empty($diffIteration));
		$this->assertFalse($diffIteration == $control);
		
		$diffIteration2 = OpauthStrategy::hash($input, $timestamp, 99999, $salt);
		$this->assertFalse(empty($diffIteration2));
		$this->assertFalse($diffIteration2 == $control);
		$this->assertFalse($diffIteration2 == $diffIteration);
		
		$diffIteration3 = OpauthStrategy::hash($input, $timestamp, 0, $salt);
		$this->assertFalse($diffIteration3);
		
		// Ensure salt is taken into account and producing different hash
		$diffSalt = OpauthStrategy::hash($input, $timestamp, $iteration, 'a98woj34 89789&SFDIU(@&*#(*@$');
		$this->assertFalse(empty($diffSalt));
		$this->assertFalse($diffSalt == $control);
		
		$diffSalt2 = OpauthStrategy::hash($input, $timestamp, $iteration, null);
		$this->assertFalse(empty($diffSalt2));
		$this->assertFalse($diffSalt2 == $control);
		$this->assertFalse($diffSalt2 == $diffSalt);
	}
	
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testExpectsMissingConfig() {
		$config = OpauthTest::configForTest();
		
		unset($config['Strategy']['Sample']['sample_id']);
		$this->assertFalse(isset($config['Strategy']['Sample']['sample_id']));
		
		$config['path'] = '/sample';
		
		$Opauth = OpauthTest::instantiateOpauthForTesting($config);
		$this->assertFalse(isset($Opauth->env['Strategy']['Sample']['sample_id']));
		$this->assertEquals($Opauth->env['path'], '/sample');
		
		$Opauth->run();
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function testRedirect(){
		$randomUrl = 'http://random.test?r='.rand();
		
		$headers_list = xdebug_get_headers();
		$this->assertNotContains("Location: $randomUrl", $headers_list);
		
		OpauthStrategy::redirect($randomUrl, false);
		$headers_list = xdebug_get_headers();
		$this->assertContains("Location: $randomUrl", $headers_list);
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function testClientGet(){
		$url = 'http://example.test.org';
		$data = array(
			'abc' => 'def',
			'hello' => array(
				'world',
				'mars' => 'attack'
			),
			'more""funny ' => 'element$"'
		);
		$fullUrl = $url.'?'.http_build_query($data, '', '&');
		
		$headers_list = xdebug_get_headers();
		$this->assertNotContains("Location: $url", $headers_list);
		$this->assertNotContains("Location: $fullUrl", $headers_list);
		
		OpauthStrategy::clientGet($url, $data, false);
		$headers_list = xdebug_get_headers();
		$this->assertNotContains("Location: $url", $headers_list);
		$this->assertContains("Location: $fullUrl", $headers_list);
	}
	
	/**
	 * Instantiate OpauthStrategy with test config suitable for testing
	 * 
	 * @param array $config Config changes to be merged with the default
	 * @param boolean $autoRun Should Opauth be run right after instantiation, defaulted to false
	 * @return object Opauth instance
	 */
	protected static function instantiateSampleStrategyForTesting($config = array(), $autoRun = false){
		$Opauth = new Opauth(self::configForTest($config), $autoRun);
		
		// From Opauth.php
		$strategy = $Opauth->env['Strategy']['Sample'];
		$safeEnv = $Opauth->env;
		unset($safeEnv['Strategy']);
		
		require_once './tests/Opauth//Strategy/Sample/SampleStrategy.php';
		$OpauthStrategy = new SampleStrategy($strategy, $safeEnv);
		return $OpauthStrategy;
	}
	
}
