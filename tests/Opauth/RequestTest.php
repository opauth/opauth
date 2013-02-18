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
use \PHPUnit_Framework_TestCase;

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Opauth/autoload.php';
$loader = new ClassLoader('Opauth', dirname(dirname(dirname(__FILE__))) . '/lib');
$loader->register();
unset($loader);

/**
 * OpauthTest class
 */
class RequestTest extends PHPUnit_Framework_TestCase {

	/**
	 * Setup
	 */
	protected function setUp(){
		// To surpress E_USER_NOTICE on missing $_SERVER indexes
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/auth/test_provider/callback';
	}

	/**
	 * testConstruct
	 */
	public function testConstruct() {
		$request = new Request();
		$this->assertEquals('test_provider', $request->provider);
		$this->assertEquals('callback', $request->action);

		$_SERVER['REQUEST_URI'] = '/auth/test_provider';
		$request = new Request();
		$this->assertEquals('test_provider', $request->provider);
		$this->assertNull($request->action);
	}

	/**
	 * testConstructException
	 *
	 * @expectedExceptionMessage Not an Opauth request, path is not in uri
	 * @expectedException Exception
	 */
	public function testConstructException() {
		$_SERVER['REQUEST_URI'] = '/';
		$request = new Request();
	}

	/**
	 * testGetHost
	 */
	public function testGetHost() {
		$request = new Request();
		$this->assertEquals('http://test.example.org', $request->getHost());
	}

	/**
	 * testProviderUrl
	 */
	public function testProviderUrl() {
		$request = new Request();
		$this->assertEquals('http://test.example.org/auth/test_provider', $request->providerUrl());

		$_SERVER['REQUEST_URI'] = '/login/test_provider/callback';
		$request = new Request('/login/');
		$this->assertEquals('http://test.example.org/login/test_provider', $request->providerUrl());

		$_SERVER['REQUEST_URI'] = '/test_provider/callback';
		$request = new Request('/');
		$this->assertEquals('http://test.example.org/test_provider', $request->providerUrl());
	}
}