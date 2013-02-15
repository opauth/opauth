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
require dirname(dirname(dirname(__FILE__))) . '/lib/Opauth/autoload.php';
$loader = new ClassLoader('Opauth', dirname(dirname(dirname(__FILE__))) . '/lib');
$loader->register();
unset($loader);

/**
 * OpauthTest class
 */
class ResponseTest extends \PHPUnit_Framework_TestCase{

	protected function setUp(){
		// To surpress E_USER_NOTICE on missing $_SERVER indexes
		$_SERVER['HTTP_HOST'] = 'test.example.org';
		$_SERVER['REQUEST_URI'] = '/';
	}

	public function testConstruct() {
		$provider = 'TestProvider';
		$raw = array('some' => 'raw data');
		$response = new Response($provider, $raw);
		$this->assertEquals($provider, $response->provider);
		$this->assertEquals($raw, $response->raw);
	}

	public function testMap() {
		$response = $this->buildResponse();

		$response->map(array('some' => 'info.somename'));
		$this->assertEquals('raw data', $response->info['somename']);

		$response->map(array('more.nested' => 'info.more'));
		$this->assertEquals('raw', $response->info['more']);

		$response->map(array('id' => 'uid'));
		$this->assertEquals(1234, $response->uid);

		$response = $this->buildResponse();
		$response->map(array(
			'some' => 'info.somename',
			'more.nested' => 'info.more',
			'id' => 'uid'
		));
		$this->assertEquals('raw data', $response->info['somename']);
		$this->assertEquals('raw', $response->info['more']);
		$this->assertEquals(1234, $response->uid);
	}

	public function testSetData() {
		$response = $this->buildResponse();

		$response->setData('some', 'info.somename');
		$this->assertEquals('raw data', $response->info['somename']);

		$response->setData('more.nested', 'info.more');
		$this->assertEquals('raw', $response->info['more']);

		$response->setData('id', 'uid');
		$this->assertEquals(1234, $response->uid);

		$response->setData('id', 'nothere');
		$this->assertNull($response['nothere']);

		$response->setData('id', 'nothere');
		$this->assertNull($response->nothere);
	}

	protected function buildResponse() {
		$provider = 'TestProvider';
		$raw = array(
			'id' => 1234,
			'some' => 'raw data',
			'more' => array('nested' => 'raw'),
			'location' => 'here'
		);
		return new Response($provider, $raw);
	}
}