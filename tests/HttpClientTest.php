<?php
/**
 * OpauthHttpClientCurlTest
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Tests\HttpClient;

 /**
  * OpauthHttpClientBaseTest class
  * Tests for all shipped HTTP clients
  */
class OpauthHttpClientCurlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Curl
     */
    protected $Curl = null;

    /**
     * @var File
     */
    protected $File = null;

    /**
     * @var Guzzle
     */
    protected $Guzzle = null;

    /**
     * @var GuzzleHttp
     */
    protected $GuzzleHttp = null;

    /**
     * @var array Test URLs
     */
    protected $clients = array('Curl', 'File', 'Guzzle', 'GuzzleHttp');

    /**
     * @var array Parameters for HTTP GET or POST
     */
    protected $params = array(
        'param1' => 'value1',
        //'numericParam' => 2, //GuzzleHttp does not support integer, only string
        'numericParam' => '2',
        'utf8' => '你好'
    );

    protected function setUp()
    {
        if (ini_get('allow_url_fopen')) {
            $this->File = new \Opauth\Opauth\HttpClient\File();
        }
        if (function_exists('curl_init')) {
            $this->Curl = new \Opauth\Opauth\HttpClient\Curl();
        }
        if (class_exists('Guzzle\\Http\\Client')) {
            $this->Guzzle = new \Opauth\Opauth\HttpClient\Guzzle();
        }
        if (class_exists('GuzzleHttp\\Client')) {
            $this->GuzzleHttp = new \Opauth\Opauth\HttpClient\GuzzleHttp();
        }
    }

    public function testFileGet()
    {
        $this->checkGetCall($this->File, 'File');
    }

    public function testFilePost()
    {
        $this->checkPostCall($this->File, 'File');
    }

    public function testCurlGet()
    {
        $this->checkGetCall($this->Curl, 'Curl');
    }

    public function testCurlPost()
    {
        $this->checkGetCall($this->Curl, 'Curl');
    }

    public function testGuzzleGet()
    {
        $this->checkGetCall($this->Guzzle, 'Guzzle');
    }

    public function testGuzzlePost()
    {
        $this->checkPostCall($this->Guzzle, 'Guzzle');
    }

    public function testGuzzleHttpGet()
    {
        $this->checkGetCall($this->GuzzleHttp, 'GuzzleHttp');
    }

    public function testGuzzleHttpPost()
    {
        $this->checkPostCall($this->GuzzleHttp, 'GuzzleHttp');
    }

    protected function checkGetCall($client, $name)
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

    protected function checkPostCall($client, $name)
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
}
