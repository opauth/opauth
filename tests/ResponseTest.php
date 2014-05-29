<?php
/**
 * OpauthTest
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Tests;

use Opauth\Opauth\Response;

/**
 * OpauthTest class
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        // To surpress E_USER_NOTICE on missing $_SERVER indexes
        $_SERVER['HTTP_HOST'] = 'test.example.org';
        $_SERVER['REQUEST_URI'] = '/auth/';
    }

    public function testConstruct()
    {
        $provider = 'TestProvider';
        $raw = array('some' => 'raw data');
        $response = new Response($provider, $raw);
        $this->assertEquals($provider, $response->provider);
        $this->assertEquals($raw, $response->raw);
    }

    public function testMapping()
    {
        $response = $this->buildResponse();

        $map = array('info.somename' => 'some');
        $response->setMap($map);
        $this->assertSame($map, $response->getMap());

        $response->map();
        $this->assertEquals('raw data', $response->info['somename']);

        $response->setMap(array('info.more' => 'more.nested'));
        $response->map();
        $this->assertEquals('raw', $response->info['more']);

        $response->setMap(array('uid' => 'id'));
        $response->map();
        $this->assertEquals(1234, $response->uid);

        $response = $this->buildResponse();
        $map = array(
            'info.somename' => 'some',
            'info.more' => 'more.nested',
            'uid' => 'id'
        );
        $response->setMap($map);
        $this->assertSame($map, $response->getMap());

        $response->map();
        $this->assertEquals('raw data', $response->info['somename']);
        $this->assertEquals('raw', $response->info['more']);
        $this->assertEquals(1234, $response->uid);
    }

    public function testSetData()
    {
        $response = $this->buildResponse();

        $response->setData('info.somename', 'some');
        $this->assertEquals('raw data', $response->info['somename']);

        $response->setData('info.more', 'more.nested');
        $this->assertEquals('raw', $response->info['more']);

        $response->setData('uid', 'id');
        $this->assertEquals(1234, $response->uid);

        $response->setData('nothere', 'id');
        $this->assertNull($response['nothere']);

        $response->setData('nothere', 'id');
        $this->assertNull($response->nothere);
    }

    public function testIsValid()
    {
        $response = $this->buildResponse();
        $this->assertFalse($response->isValid());

        $response->uid = 'id';
        $response->name = 'fullname';
        $response->credentials = array('token' => 'token', 'secret' => 'secret');
        $this->assertTrue($response->isValid());
    }

    protected function buildResponse()
    {
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
