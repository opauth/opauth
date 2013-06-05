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
		$this->assertEquals('http://test.example.org', $Opauth->Request->getHost());

		$_SERVER['HTTP_HOST'] = 'test2.example.org';
		$_SERVER['REQUEST_URI'] = '/subdir/auth/sample';
		$Opauth = new Opauth(array(
			'path' => '/subdir/auth/',
		));
		$this->assertEquals('http://test2.example.org', $Opauth->Request->getHost());
		$this->assertEquals('http://test2.example.org/subdir/auth/sample', $Opauth->Request->providerUrl());
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
	 *
	 */
	public function testRunRequest() {
		$_SERVER['REQUEST_URI'] = '/auth/sample';
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
		$this->expectOutputString('request() called');
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

}