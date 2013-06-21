<?php
/**
 * OpauthTest
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.OpauthTest
 * @license      MIT License
 */

require './lib/Opauth/Opauth.php';

/**
 * OpauthTest class
 */
class OpauthTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		// To surpress E_USER_NOTICE on missing $_SERVER indexes
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/';
	}

	public function testConstructor() {
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
	public function testConstructorDefaultSecuritySalt() {
		$Opauth = self::instantiateOpauthForTesting(array(
			'security_salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
		));
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testConstructorAutoRunWithoutStrategies() {
		$Opauth = self::instantiateOpauthForTesting(array(), true);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testRunWithoutRequest() {
		$Opauth = self::instantiateOpauthForTesting(array(), true);
	}

	public function testRun() {
		$config = array(
			'path' => '/authenticate/'
		);

		$_SERVER['REQUEST_URI'] = '/authenticate/sample';

		$this->expectOutputString('request() called');
		$Opauth = self::instantiateOpauthForTesting($config, true);
	}

	public function testRunNonExistingRequest() {
		$config = array(
			'path' => '/'
		);

		$_SERVER['REQUEST_URI'] = '/sample/non_existing';

		$this->expectOutputString('request() called');
		$Opauth = self::instantiateOpauthForTesting($config, true);
	}

	public function testRunSpecificRequest() {
		$config = array(
			'path' => '/'
		);

		$_SERVER['REQUEST_URI'] = '/sample/arbritary';

		$this->expectOutputString('arbritary() called');
		$Opauth = self::instantiateOpauthForTesting($config, true);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testRunNonExistingStrategy() {
		$config = array(
			'path' => '/'
		);

		$_SERVER['REQUEST_URI'] = '/non-existing-strategy/request';
		$Opauth = self::instantiateOpauthForTesting($config, true);
	}

	public function testRunExplicitRequestAsConfig() {
		$config = array(
			'path' => '/',
			'request_uri' => '/sample'
		);

		// This should be ignored
		$_SERVER['REQUEST_URI'] = '/non-existing-strategy/request';

		$this->expectOutputString('request() called');
		$Opauth = self::instantiateOpauthForTesting($config, true);
	}

	public function testValidate() {
		$response = array(
			'auth' => array(
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
			),
			'timestamp' => null,
			'signature' => null
		);

		$config = array(
			'security_salt' => 'k9QVRc7R3woOOVyJgOFBv2Rp9bxQsGtRbaOraP7ePXuyzh0GkrNckKjI4MV1KOy',
			'security_iteration' => 919,
			'security_timeout' => '1 minute'
		);

		$response['timestamp'] = date('c');
		$response['signature'] = OpauthStrategy::hash(sha1(print_r($response['auth'], true)), $response['timestamp'], $config['security_iteration'], $config['security_salt']);

		$Opauth = self::instantiateOpauthForTesting($config);
		$this->assertTrue($Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason));
		$this->assertNull($reason);

		return $response;
	}

	/**
	 * @depends testValidate
	 */
	public function testValidateTimeout(array $response) {
		$config = array(
			'security_salt' => 'k9QVRc7R3woOOVyJgOFBv2Rp9bxQsGtRbaOraP7ePXuyzh0GkrNckKjI4MV1KOy',
			'security_iteration' => 919,
			'security_timeout' => '1 minute'
		);

		$response['timestamp'] = date('c', time() - 90);
		$response['signature'] = OpauthStrategy::hash(sha1(print_r($response['auth'], true)), $response['timestamp'], $config['security_iteration'], $config['security_salt']);

		$Opauth = self::instantiateOpauthForTesting($config);
		$this->assertFalse($Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason));
		$this->assertEquals($reason, 'Auth response expired');
	}

	/**
	 * @depends testValidate
	 */
	public function testValidateInvalidSignature(array $response) {
		$config = array(
			'security_salt' => 'k9QVRc7R3woOOVyJgOFBv2Rp9bxQsGtRbaOraP7ePXuyzh0GkrNckKjI4MV1KOy',
			'security_iteration' => 919,
			'security_timeout' => '1 minute'
		);

		$response['timestamp'] = date('c');
		$response['signature'] = 'invalidsignature';

		$Opauth = self::instantiateOpauthForTesting($config);
		$this->assertFalse($Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason));
		$this->assertEquals($reason, 'Signature does not validate');
	}

	public function testLoadStrategies() {
		$config = array('Strategy' => array(
			'ProviderA' => array(
				'hello' => 'world',
				'integer_value' => 123,
				'more_arrays' => array(
					'key1' => 'v1',
					'key2' => 2
				)
			)
		));

		$Opauth = self::instantiateOpauthForTesting($config);
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

		// Explicitly set internal values
		$config['Strategy']['ProviderA']['strategy_class'] = 'AnotherClass';
		$config['Strategy']['ProviderA']['strategy_name'] = 'DifferentName';
		$config['Strategy']['ProviderA']['strategy_url_name'] = 'Hello';
		$Opauth = self::instantiateOpauthForTesting($config);

		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['strategy_class'], 'AnotherClass');
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['strategy_name'], 'ProviderA');
		$this->assertEquals($Opauth->env['Strategy']['ProviderA']['strategy_url_name'], 'Hello');
	}

	public function testLoadStrategiesAsString() {
		$Opauth = self::instantiateOpauthForTesting(array(
			'Strategy' => array('ProviderAsString')
		));

		$this->assertEquals($Opauth->env['Strategy']['ProviderAsString']['strategy_class'], 'ProviderAsString');
		$this->assertEquals($Opauth->env['Strategy']['ProviderAsString']['strategy_name'], 'ProviderAsString');
		$this->assertEquals($Opauth->env['Strategy']['ProviderAsString']['strategy_url_name'], 'providerasstring');
		$this->assertEquals(count($Opauth->env['Strategy']['ProviderAsString']), 3);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testLoadStrategiesError() {
		$config = self::configForTest();
		unset($config['Strategy']);
		$Opauth = new Opauth($config, false);
	}

	public function testDebugWithDebugOn() {
		$Opauth = self::instantiateOpauthForTesting(array(
			'debug' => true
		));
		$this->expectOutputString('<pre>Debug message</pre>');
		$Opauth->debug('Debug message');
	}

	public function testDebugWithDebugOff() {
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
	protected static function configForTest($config = array()) {
		return array_merge(array(
			'host' => 'http://test.example.org',
			'path' => '/',
			'security_salt' => 'testing-salt',
			'strategy_dir' => dirname(__FILE__).'/Strategy/',

			'Strategy' => array(
				'Sample' => array(
					'sample_id' => 'test_id',
					'sample_secret' => 'test_secret'
				)
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
	protected static function instantiateOpauthForTesting($config = array(), $autoRun = false) {
		$Opauth = new Opauth(self::configForTest($config), $autoRun);

		return $Opauth;
	}

}