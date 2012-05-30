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
			'path' => '/auth/',
		));
		$this->assertEquals($Opauth->env['host'], 'http://test2.example.com');
		$this->assertEquals($Opauth->env['complete_path'], 'http://test2.example.com/auth/');
		$this->assertEquals($Opauth->env['callback_url'], '/auth/callback');
		
		$Opauth = self::instantiateOpauthForTesting(array(
			'security_salt' => 'another salt',
			'security_iteration' => 3456,
			'security_timeout' => '5 seconds'
		));
		$this->assertEquals($Opauth->env['security_salt'], 'another salt');
		$this->assertEquals($Opauth->env['security_iteration'], 3456);
		$this->assertTrue(is_int($Opauth->env['security_iteration']));
		$this->assertEquals($Opauth->env['security_timeout'], '5 seconds');
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testConstructorDefaultSecuritySalt(){
		$Opauth = self::instantiateOpauthForTesting(array(
			'security_salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
		));
	}
	
	public function testLoadStrategies(){
		$Opauth = self::instantiateOpauthForTesting(array(
			'Strategy' => array(
				'ProviderA' => array(
					'hello' => 'world',
					'integer_value' => 123,
					'more_arrays' => array(
						'key1' => 'v1',
						'key2' => 2
					)
				)
			)
		));
		$this->assertArrayHasKey('ProviderA', $Opauth->env['Strategy']);
		$this->assertEquals(count($Opauth->env['Strategy']), 1);
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['hello'], 'world');
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['integer_value'], 123);
		$this->assertTrue(is_int($Opauth->env['Strategy']['ProviderA']['integer_value']));
		$this->assertTrue(is_array($Opauth->env['Strategy']['ProviderA']['more_arrays']));
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['more_arrays']['key1'], 'v1');
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['more_arrays']['key2'], 2);
		$this->assertTrue(is_int($Opauth->env['Strategy']['ProviderA']['more_arrays']['key2']));
		
		// Values set by Opauth
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['strategy_class'], 'ProviderA');
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['strategy_name'], 'ProviderA');
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['strategy_url_name'], 'providera');
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