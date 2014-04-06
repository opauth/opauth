<?php
/**
 * HttpClientTest
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Tests;

 /**
  * HttpClientTest class
  *
  * Tests for all shipped HTTP clients
  */
class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array Parameters for HTTP GET or POST
     */
    protected $params = array(
        'param1' => 'value1',
        //'numericParam' => 2, //Guzzle does not support integer, only string
        'numericParam' => '2',
        'utf8' => '你好'
    );

    /**
     * @param $client
     * @param $name
     * @dataProvider clientProvider
     */
    public function testGet($client, $name)
    {
        if (is_null($client)) {
            $this->markTestSkipped(
                $name. ' HTTP client is not available.'
            );
        }
        $url = 'http://httpbin.org/get';
        $response = $client->get($url, $this->params);
        $response = json_decode($response);
        $this->assertContains('Opauth', $response->headers->{'User-Agent'});
        foreach ($this->params as $key => $value) {
            $this->assertEquals($response->args->{$key}, $value);
        }
    }

    /**
     * @param $client
     * @param $name
     * @dataProvider clientProvider
     */
    public function testPost($client, $name)
    {
        if (is_null($client)) {
            $this->markTestSkipped(
                $name. ' HTTP client is not available.'
            );
        }
        $url = 'http://httpbin.org/post';
        $response = $client->post($url, $this->params);
        $response = json_decode($response);
        $this->assertContains('Opauth', $response->headers->{'User-Agent'});
        foreach ($this->params as $key => $value) {
            $this->assertEquals($response->form->{$key}, $value);
        }
    }

    public function clientProvider()
    {
        return array(
            array(ini_get('allow_url_fopen') ? new \Opauth\Opauth\HttpClient\File() : null, 'File'),
            array(function_exists('curl_init') ? new \Opauth\Opauth\HttpClient\Curl() : null, 'Curl'),
            array(class_exists('Guzzle\\Http\\Client') ? new \Opauth\Opauth\HttpClient\Guzzle3() : null, 'Guzzle3'),
            array(class_exists('GuzzleHttp\\Client') ? new \Opauth\Opauth\HttpClient\Guzzle() : null, 'Guzzle')
        );
    }
}
