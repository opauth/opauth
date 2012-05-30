<?php
/**
 * OpauthTest
 *
 * @copyright		Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link 			http://opauth.org
 * @package			Opauth
 * @license			MIT License
 */

require '../../lib/Opauth/Opauth.php';

/**
 * OpauthTest class
 */
class OpauthTest extends PHPUnit_Framework_TestCase{

	protected function setUp(){
		// To surpress E_USER_NOTICE on missing $_SERVER indexes
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/';
	}
	
	public function testConstructor(){
		$Opauth = self::instantiateOpauthForTesting();
		$this->assertEquals($Opauth->env['host'], 'http://test.example.org');
		$this->assertEquals($Opauth->env['path'], '/');
		$this->assertEquals($Opauth->env['request_uri'], '/');
		$this->assertEquals($Opauth->env['callback_url'], '/callback');
		$this->assertEquals($Opauth->env['callback_transport'], 'session');
		$this->assertEquals($Opauth->env['debug'], false);
		$this->assertFalse($Opauth->env['debug']);
		$this->assertEquals($Opauth->env['security_salt'], 'testing-salt');
		$this->assertEquals($Opauth->env['security_iteration'], 300);
		$this->assertEquals($Opauth->env['security_timeout'], '2 minutes');
		
		$Opauth = self::instantiateOpauthForTesting(array(
			'host' => 'http://test2.example.com',
			'path' => '/auth/'
		));
		$this->assertEquals($Opauth->env['host'], 'http://test2.example.com');
		$this->assertEquals($Opauth->env['complete_path'], 'http://test2.example.com/auth/');
		$this->assertEquals($Opauth->env['callback_url'], '/auth/callback');
	}
	
	public function testDebugWithDebugOn(){
		$Opauth = self::instantiateOpauthForTesting(array(
			'debug' => true
		));
		$this->expectOutputString('<pre>Debug message</pre>');		
		$Opauth->debug('Debug message');
	}
	
	public function testDebugWithDebugOff(){
		$Opauth = self::instantiateOpauthForTesting(array(
			'debug' => false
		));
		$this->expectOutputString('');
		$Opauth->debug('Debug message');
	}
	
	/**
	 * Make an Opauth config with basic parameters suitable for testing,
	 * especially those that are related to HTTP
	 * 
	 * @param array $config Config changes to be merged with the default
	 * @return array Merged config
	 */
	protected static function configForTest($config = array()){
		return array_merge(array(
			'host' => 'http://test.example.org',
			'path' => '/',
			
			'security_salt' => 'testing-salt',
			
			'Strategy' => array(
				'Test' => array()
			)
		), $config);
	}
	
	/**
	 * Instantiate Opauth with test config suitable for testing
	 * 
	 * @param array $config Config changes to be merged with the default
	 * @param boolean $autoRun Should Opauth be run right after instantiation, defaulted to false
	 * @return object Opauth instance
	 */
	protected static function instantiateOpauthForTesting($config = array(), $autoRun = false){
		$Opauth = new Opauth(self::configForTest($config), $autoRun);
		
		return $Opauth;
	}
	
}