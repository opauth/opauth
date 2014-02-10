<?php
/**
 * OpauthTest
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.OpauthTest
 * @license      MIT License
 */
namespace Opauth\Opauth\Tests\Request;

use Opauth\Opauth\Request\Parser;

/**
 * OpauthTest class
 */
class ParserTest extends \PHPUnit_Framework_TestCase {

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
		$request = new Parser('/auth/');
		$this->assertEquals('test_provider', $request->urlname());
		$this->assertEquals('callback', $request->action());

		$_SERVER['REQUEST_URI'] = '/auth/test_provider';
		$request = new Parser('/auth/');
		$this->assertEquals('test_provider', $request->urlname());
		$this->assertNull($request->action());
	}

	/**
	 * testConstructException
	 *
	 * @expectedExceptionMessage Not an Opauth request, path is not in uri
	 * @expectedException Exception
	 */
	public function testConstructException() {
		$_SERVER['REQUEST_URI'] = '/';
		$request = new Parser('/auth/');
	}

	/**
	 * testGetHost
	 * @covers Opauth\Opauth\Request\Parser::getHost
	 */
	public function testGetHost() {
		$request = new Parser('/auth/');
		$this->assertEquals('http://test.example.org/auth/test_provider', $request->providerUrl());
	}

	/**
	 * testProviderUrl
	 */
	public function testProviderUrl() {
		$request = new Parser('/auth/');
		$this->assertEquals('http://test.example.org/auth/test_provider', $request->providerUrl());

		$_SERVER['REQUEST_URI'] = '/login/test_provider/callback';
		$request = new Parser('/login/');
		$this->assertEquals('http://test.example.org/login/test_provider', $request->providerUrl());

		$_SERVER['REQUEST_URI'] = '/test_provider/callback';
		$request = new Parser();
		$this->assertEquals('http://test.example.org/test_provider', $request->providerUrl());
	}
}