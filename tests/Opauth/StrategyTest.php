<?php
/**
 * OpauthStrategyTest
 *
 * @copyright	Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link		 http://opauth.org
 * @package	  Opauth.OpauthStrategyTest
 * @license	  MIT License
 */
namespace Opauth;
use \PHPUnit_Framework_TestCase;
use Opauth\Request;
use Opauth\Strategy\Sample\Strategy;

require_once dirname(__FILE__) . '/Strategy/Sample/Strategy.php';
/**
 * OpauthTest class
 */
class StrategyTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/auth/sample';
		$config = array(
			'sample_id' => 1234,
			'sample_secret' => 'fortytwo',
			'provider' => 'Sample'
		);
		$this->Strategy = new Strategy($config);
		$Request = new Request('/auth/');
		$this->Strategy->callbackUrl($Request->providerUrl() . '/callback');
	}

	public function tearDown() {
		if (isset($_SESSION['_opauth_dataSample'])) {
			unset($_SESSION['_opauth_dataSample']);
		}
	}

	/**
	 * @expectedException Exception
	 */
	public function testConstructMissingKeys() {
		new Strategy(array());
	}

	public function testCallbackUrl() {
		$expected = 'http://test.example.org/auth/sample/callback';
		$result = $this->Strategy->callbackUrl();
		$this->assertEquals($expected, $result);
	}

	public function testAddParams() {
		$expected = array('sample_id' => 1234);
		$result = $this->Strategy->addParams(array('sample_id', 'not_existing'));
		$this->assertEquals($expected, $result);

		$result = $this->Strategy->addParams(array('sample_id' => 'sample_id_alias'));
		$this->assertEquals(array('sample_id_alias' => 1234), $result);

		$result = $this->Strategy->addParams(array('sample_id' => 'sample_id_alias', 'sample_id', 'not_existing'));
		$this->assertEquals(array('sample_id_alias' => 1234, 'sample_id' => 1234), $result);
	}

	public function testSessionData() {
		$this->Strategy->sessionData('opauthdata');
		$result = $_SESSION['_opauth_dataSample'];
		$this->assertEquals('opauthdata', $result);

		$result = $this->Strategy->sessionData();
		$this->assertEquals('opauthdata', $result);
	}

	public function testResponse() {
		$result = $this->Strategy->response('rawdata');
		$this->assertInstanceof('Opauth\\Response', $result);
		$this->assertFalse($result->isError());

		$result = $this->Strategy->response('rawdata', array('code' => 12, 'message' => 'errormessage'));
		$this->assertInstanceof('Opauth\\Response', $result);
		$this->assertTrue($result->isError());
	}

}