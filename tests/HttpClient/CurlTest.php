<?php
/**
 * OpauthHttpClientCurlTest
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Tests\HttpClient;

use Opauth\Opauth\HttpClient\Curl;

 /**
  * OpauthHttpClientBaseTest class
  */
 class OpauthHttpClientCurlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Curl
     */
    protected $Curl;

    /**
     * @var array Parameters for HTTP GET or POST
     */
    protected $params = array(
        'param1' => 'value1',
        'numericParam' => 2,
        'utf8' => '你好'
    );

    public function setUp()
    {
        $this->Curl = new Curl();
    }

    public function testGet()
    {
        $url = 'http://httpbin.org/get';
        $response = $this->Curl->get($url, $this->params);
        $response = json_decode($response);
        $this->assertContains('Opauth', $response->headers->{'User-Agent'});
        foreach ($this->params as $key => $value) {
            $this->assertEquals($response->args->{$key}, $value);
        }
    }

    public function testPost()
    {
        $url = 'http://httpbin.org/post';
        $response = $this->Curl->post($url, $this->params);
        $response = json_decode($response);
        $this->assertContains('Opauth', $response->headers->{'User-Agent'});
        foreach ($this->params as $key => $value) {
            $this->assertEquals($response->form->{$key}, $value);
        }
    }
}
