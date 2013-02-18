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
use Opauth\Provider\Sample\Strategy;

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Opauth/autoload.php';
$loader = new ClassLoader('Opauth', dirname(dirname(dirname(__FILE__))) . '/lib');
$loader->register();
unset($loader);

require_once dirname(__FILE__) . '/Provider/Sample/Strategy.php';
/**
 * OpauthTest class
 */
class StrategyTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/auth/sample';
		$config = array(
			'sample_id' => 1234,
			'sample_secret' => 'fortytwo'
		);
		$this->Strategy = new Strategy(new Request(), $config);
	}

	public function tearDown() {
		if (isset($_SESSION['_opauth_data'])) {
			unset($_SESSION['_opauth_data']);
		}
	}

	/**
	 * @expectedException Exception
	 */
	public function testConstructMissingKeys() {
		new Strategy(new Request(), array());
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
	}

	public function testSessionData() {
		$this->Strategy->sessionData('opauthdata');
		$result = $_SESSION['_opauth_data'];
		$this->assertEquals('opauthdata', $result);

		$result = $this->Strategy->sessionData();
		$this->assertEquals('opauthdata', $result);
	}

}