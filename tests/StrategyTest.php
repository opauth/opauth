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
        $client = $this->getMock('Opauth\\Opauth\\HttpClientInterface');
        $this->Strategy = new Sample($config, $callbackUrl, $client);
    }

    public function tearDown()
    {
        if (isset($_SESSION['_opauth_dataSample'])) {
            unset($_SESSION['_opauth_dataSample']);
        }
    }

    /**
     * @expectedException Opauth\Opauth\OpauthException
     */
    public function testCheckMissingExpected()
    {
        $config = array(
            'provider' => 'Sample',
            'sample_id' => 1234,
        );
        $Request = new Parser('/auth/');
        $callbackUrl = $Request->providerUrl() . '/callback';
        $client = $this->getMock('Opauth\\Opauth\\HttpClientInterface');
        $Strategy = new Sample($config, $callbackUrl, $client);
    }

    public function testGetHttpClient()
    {
        $this->assertInstanceOf('Opauth\\Opauth\\HttpClientInterface', $this->Strategy->getHttpClient());
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

    public function testRecursiveGetObjectVars()
    {
        $object = new \stdClass();
        $object->first_level = 'level 1';
        $object->another = new \stdClass();
        $object->another->second_level = 'level 2';
        $object->another->utf8_string = '第二';
        $object->another->numeric = 987;
        $object->another->boolean_true_value = true;
        $object->another->boolean_false_value = false;
        $object->another->some_array = array(
            'string' => 'This is a string.',
            'utf8' => '你好',
            'true_val' => true,
            'false_val' => false
        );
        $this->assertFalse(is_array($object));
        $this->assertTrue(is_object($object));

        $array = $this->Strategy->recursiveGetObjectVars($object);
        $this->assertTrue(is_array($array));
        $this->assertFalse(is_object($array));
        $this->assertTrue(is_array($array['another']));
        $expected = array(
            'first_level' => 'level 1',
            'another' => array(
                'second_level' => 'level 2',
                'utf8_string' => '第二',
                'numeric' => 987,
                'boolean_true_value' => 1,
                'boolean_false_value' => 0,
                'some_array' => array(
                    'string' => 'This is a string.',
                    'utf8' => '你好',
                    'true_val' => 1,
                    'false_val' => 0
                )
            )
        );
        $this->assertEquals($array, $expected);
    }

    public function testEnvReplace()
    {
        $dictionary = array(
            'provider' => 'Sample',
            'sample_id' => 1234,
        );

        $result = $this->Strategy->envReplace('in {provider}-between', $dictionary);
        $this->assertEquals('in Sample-between', $result);
        $result = $this->Strategy->envReplace('Numeric-{sample_id}', $dictionary);
        $this->assertEquals('Numeric-1234', $result);
        $result = $this->Strategy->envReplace('Multi: {provider} & {sample_id}', $dictionary);
        $this->assertEquals('Multi: Sample & 1234', $result);
        $result = $this->Strategy->envReplace('Incomplete brackets: {provider}-{sample_id', $dictionary);
        $this->assertEquals('Incomplete brackets: Sample-{sample_id', $result);
        $result = $this->Strategy->envReplace(6484265, $dictionary);
        $this->assertEquals(6484265, $result);

    }
}
