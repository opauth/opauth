<?php
/**
 * OpauthTest
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.OpauthTest
 * @license      MIT License
 */

namespace Opauth;
use Opauth\Opauth;
use \PHPUnit_Framework_TestCase;
use Opauth\Strategy\Sample\Strategy;

require_once dirname(__FILE__) . '/Strategy/Sample/Strategy.php';

/**
 * OpauthTest class
 */
class OpauthTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		// To surpress E_USER_NOTICE on missing $_SERVER indexes
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/auth/sample';
	}

	public function testConstructor() {
		$Opauth = new Opauth(array());
		$this->assertEquals('http://test.example.org', $Opauth->request->getHost());

		$_SERVER['HTTP_HOST'] = 'test2.example.org';
		$_SERVER['REQUEST_URI'] = '/subdir/auth/sample';
		$Opauth = new Opauth(array(
			'path' => '/subdir/auth/',
		));
		$this->assertEquals('http://test2.example.org', $Opauth->request->getHost());
		$this->assertEquals('http://test2.example.org/subdir/auth/sample', $Opauth->request->providerUrl());
	}

	/**
	 * @expectedExceptionMessage No strategies configured
	 * @expectedException Exception
	 */
	public function testRunWithoutStrategies() {
		$Opauth = new Opauth(array());
		$Opauth->run();
	}

	/**
	 * @expectedExceptionMessage Unsupported or undefined Opauth strategy - notsample
	 * @expectedException Exception
	 */
	public function testRunWrongAuthPath() {
		$_SERVER['REQUEST_URI'] = '/auth/notsample';
		$config = array(
			'Strategy' => array(
				'Sample' => array(
					'sample_id' => 1234,
					'sample_secret' => 'fortytwo'
				)
			)
		);
		$Opauth = new Opauth($config);
		$Opauth->run();
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Error from strategy
	 */
	public function testRunRequest() {
		$_SERVER['REQUEST_URI'] = '/auth/sample';
		$config = array(
			'Strategy' => array(
				'Sample' => array(
					'sample_id' => 1234,
					'sample_secret' => 'fortytwo',
					'return' => true
				)
			)
		);
		$Opauth = new Opauth($config);
		$Opauth->run();
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Strategy request should redirect or return Response with error
	 */
	public function testRunRequestError() {
		$_SERVER['REQUEST_URI'] = '/auth/sample';
		$config = array(
			'Strategy' => array(
				'Sample' => array(
					'sample_id' => 1234,
					'sample_secret' => 'fortytwo',
					'return' => false
				)
			)
		);
		$Opauth = new Opauth($config);
		$Opauth->run();
	}

	/**
	 *
	 * @expectedExceptionMessage Invalid response, missing required parameters
	 * @expectedException Exception
	 */
	public function testRunCallbackInvalidResponse() {
		$_SERVER['REQUEST_URI'] = '/auth/sample/callback';
		$config = array(
			'Strategy' => array(
				'Sample' => array(
					'sample_id' => 1234,
					'sample_secret' => 'fortytwo'
				)
			)
		);
		$Opauth = new Opauth($config);
		$result = $Opauth->run();
	}

	/**
	 *
	 */
	public function testRunCallback() {
		$_SERVER['REQUEST_URI'] = '/auth/sample/callback';
		$config = array(
			'sample_id' => 1234,
			'sample_secret' => 'fortytwo',
			'provider' => 'Sample',
		);
		$Opauth = new Opauth(array());
		$Strategy = new Strategy($config);
		$Strategy->testRaw = array('id' => 1, 'username' => 'sampling', 'creds' => 'credential array');
		$Strategy->testMap = array('uid' => 'id', 'name' => 'username', 'credentials' => 'creds');
		$Opauth->setStrategy($Strategy);

		$result = $Opauth->run();
		$this->assertInstanceOf('Opauth\\Response', $result);
	}

	/**
	 *
	 */
	public function testBuildStrategies() {
		$Opauth = new Opauth();

		$strategies = array(
			'Providername' => 'settings',
			'Other' => 'settings',
		);
		$result = $Opauth->buildStrategies($strategies);
		$this->assertTrue($result);
	}

		/**
	 * @expectedException Exception
	 * @expectedExceptionMessage No strategies found
	 */
	public function testBuildStrategiesException() {
		$Opauth = new Opauth();
		$Opauth->buildStrategies('wrong');
	}

	/**
	 *
	 */
	public function testBuildStrategy() {
		$Opauth = new Opauth();

		$result = $Opauth->buildStrategy('Sample', array('settings' => 'here'));
		$expected = array(
			'settings' => 'here',
			'provider' => 'Sample',
			'_name' => 'Sample',
			'_url_name' => 'sample',
			'_enabled' => true
		);
		$this->assertSame($expected, $result);

		$settings = array(
			'_name' => 'Other',
			'_url_name' => 'alias',
			'_enabled' => false
		);
		$result = $Opauth->buildStrategy('Sample', $settings);
		$expected = array(
			'_name' => 'Other',
			'_url_name' => 'alias',
			'_enabled' => false,
			'provider' => 'Sample',
		);
		$this->assertSame($expected, $result);
	}

}