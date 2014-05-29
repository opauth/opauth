<?php
/**
 * OpauthTest
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */

namespace Opauth\Opauth\Tests;

use Opauth\Opauth\Opauth;
use Opauth\Opauth\Tests\Strategy\Sample;

/**
 * OpauthTest class
 */
class OpauthTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        // To surpress E_USER_NOTICE on missing $_SERVER indexes
        $_SERVER['HTTP_HOST'] = 'test.example.org';
        $_SERVER['REQUEST_URI'] = '/auth/sample';
    }

    /**
     * @covers Opauth\Opauth\Opauth::__construct
     */
    public function testConstructorConfigs()
    {
        $_SERVER['REQUEST_URI'] = '/subdir/auth/sample';
        $Opauth = new Opauth(
            array(
                'path' => '/subdir/auth/',
            )
        );
        $this->assertSame('/subdir/auth/', $Opauth->config('path'));
    }

    /**
     * @covers Opauth\Opauth\Opauth::__construct
     */
    public function testConstructorStrategy()
    {
        $configs = array(
            'Strategy' => array('Dummy' => array('key' => 'value')),
        );
        $stub = $this->getMockBuilder('Opauth\\Opauth\\Opauth')
            ->setMethods(array('buildStrategies'))
            ->disableOriginalConstructor()
            ->getMock();
        $stub->expects($this->once())
            ->method('buildStrategies')
            ->with($configs['Strategy']);
        $stub->__construct($configs);
    }

    /**
     * @expectedExceptionMessage No strategy found in url
     * @expectedException Opauth\Opauth\OpauthException
     * @covers Opauth\Opauth\Opauth::run
     */
    public function testRunNoStrategy()
    {
        $_SERVER['REQUEST_URI'] = '/auth/';
        $Opauth = new Opauth(array());
        $Opauth->run();
    }

    /**
     * @expectedExceptionMessage No strategies configured
     * @expectedException Opauth\Opauth\OpauthException
     * @covers Opauth\Opauth\Opauth::loadStrategy
     */
    public function testLoadStrategyWithoutStrategies()
    {
        $Opauth = new Opauth(array());
        $Opauth->run();
    }

    /**
     * @expectedExceptionMessage Unsupported or undefined Opauth strategy - notsample
     * @expectedException Opauth\Opauth\OpauthException
     * @covers Opauth\Opauth\Opauth::loadStrategy
     */
    public function testLoadStrategyWrongAuthPath()
    {
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
     * @expectedException Opauth\Opauth\OpauthException
     * @expectedExceptionMessage Error from strategy during request
     * @covers Opauth\Opauth\Opauth::request
     */
    public function testRequestStrategyResponseError()
    {
        $_SERVER['REQUEST_URI'] = '/auth/sample';
        $config = array(
            'Strategy' => array(
                'Sample' => array(
                    'sample_id' => 1234,
                    'sample_secret' => 'fortytwo',
                    'force_error' => true,
                    '_name' => 'Opauth\\Opauth\\Tests\\Strategy\\Sample'
                )
            )
        );
        $Opauth = new Opauth($config);
        $Opauth->request();
    }

    /**
     *
     * @expectedExceptionMessage Invalid response, missing required parameters
     * @expectedException Opauth\Opauth\OpauthException
     * @covers Opauth\Opauth\Opauth::callback
     */
    public function testCallbackInvalidResponse()
    {
        $_SERVER['REQUEST_URI'] = '/auth/sample/callback';
        $config = array(
            'Strategy' => array(
                'Sample' => array(
                    'sample_id' => 1234,
                    'sample_secret' => 'fortytwo',
                    '_name' => 'Opauth\\Opauth\\Tests\\Strategy\\Sample'
                )
            )
        );
        $Opauth = new Opauth($config);
        $Opauth->callback();
    }

    /**
     *
     * @expectedExceptionMessage Invalid callback url element: wrongcallback
     * @expectedException Opauth\Opauth\OpauthException
     * @covers Opauth\Opauth\Opauth::run
     */
    public function testRunCallbackWrongCallback()
    {
        $_SERVER['REQUEST_URI'] = '/auth/sample/wrongcallback';
        $config = array(
            'Strategy' => array(
                'Sample' => array(
                    'sample_id' => 1234,
                    'sample_secret' => 'fortytwo',
                    '_name' => 'Opauth\\Opauth\\Tests\\Strategy\\Sample'
                )
            )
        );
        $Opauth = new Opauth($config);
        $Opauth->run();
    }

    /**
     * @covers Opauth\Opauth\Opauth::callback
     */
    public function testCallback()
    {
        $_SERVER['REQUEST_URI'] = '/auth/sample/callback';
        $config = array(
            'sample_id' => 1234,
            'sample_secret' => 'fortytwo',
            'provider' => 'Sample',
        );
        $mock = $this->getMock('Opauth\\Opauth\\HttpClientInterface');
        $Opauth = new Opauth(array());
        $Strategy = new Sample($config, 'http://test.example.org/auth/sample/callback', $mock);
        $Strategy->testRaw = array('id' => 1, 'username' => 'sampling', 'creds' => 'credential array');
        $Strategy->testMap = array('uid' => 'id', 'name' => 'username', 'credentials' => 'creds');
        $Opauth->setStrategy($Strategy);

        $result = $Opauth->callback();
        $this->assertInstanceOf('Opauth\\Opauth\\Response', $result);
    }

    /**
     * @covers Opauth\Opauth\Opauth::buildStrategies
     */
    public function testBuildStrategies()
    {
        $Opauth = new Opauth();

        $strategies = array(
            'Providername' => 'settings',
            'Other' => 'settings',
        );
        $result = $Opauth->buildStrategies($strategies);
        $this->assertTrue($result);
    }

    /**
     * @expectedException Opauth\Opauth\OpauthException
     * @expectedExceptionMessage No strategies found
     * @covers Opauth\Opauth\Opauth::buildStrategies
     */
    public function testBuildStrategiesException()
    {
        $Opauth = new Opauth();
        $Opauth->buildStrategies('wrong');
    }

    /**
     * @covers Opauth\Opauth\Opauth::buildStrategy
     */
    public function testBuildStrategy()
    {
        $Opauth = new Opauth();

        $result = $Opauth->buildStrategy('Sample', array('settings' => 'here'));
        $expected = array(
            'settings' => 'here',
            'provider' => 'Sample',
            '_name' => 'Opauth\\Sample\\Strategy\\Sample',
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
