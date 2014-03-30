<?php
/**
 * OpauthStrategyTest
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Tests;

use Opauth\Opauth\Request\Parser;
use Opauth\Opauth\Tests\Strategy\Sample;

/**
 * OpauthTest class
 */
class StrategyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Sample
     */
    protected $Strategy;

    public function setUp()
    {
        $_SERVER['HTTP_HOST'] = 'test.example.org';
        $_SERVER['REQUEST_URI'] = '/auth/sample';
        $config = array(
            'sample_id' => 1234,
            'sample_secret' => 'fortytwo',
            'provider' => 'Sample'
        );
        $Request = new Parser('/auth/');
        $callbackUrl = $Request->providerUrl() . '/callback';
        $transport = $this->getMock('Opauth\\Opauth\\TransportInterface');
        $this->Strategy = new Sample($config, $callbackUrl, $transport);
    }

    public function tearDown()
    {
        if (isset($_SESSION['_opauth_dataSample'])) {
            unset($_SESSION['_opauth_dataSample']);
        }
    }

    public function testCallbackUrl()
    {
        $expected = 'http://test.example.org/auth/sample/callback';
        $result = $this->Strategy->callbackUrl();
        $this->assertEquals($expected, $result);
    }

    public function testAddParams()
    {
        $expected = array('sample_id' => 1234);
        $result = $this->Strategy->addParams(array('sample_id', 'not_existing'));
        $this->assertEquals($expected, $result);

        $result = $this->Strategy->addParams(array('sample_id' => 'sample_id_alias'));
        $this->assertEquals(array('sample_id_alias' => 1234), $result);

        $result = $this->Strategy->addParams(array('sample_id' => 'sample_id_alias', 'sample_id', 'not_existing'));
        $this->assertEquals(array('sample_id_alias' => 1234, 'sample_id' => 1234), $result);
    }

    public function testSessionData()
    {
        $this->Strategy->sessionData('opauthdata');
        $result = $_SESSION['_opauth_dataSample'];
        $this->assertEquals('opauthdata', $result);

        $result = $this->Strategy->sessionData();
        $this->assertEquals('opauthdata', $result);
    }

    public function testResponse()
    {
        $result = $this->Strategy->response('rawdata');
        $this->assertInstanceof('Opauth\\Opauth\\Response', $result);
    }
}
