<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\HttpClient;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Client;
use Opauth\Opauth\HttpClientInterface;
use Opauth\Opauth\OpauthException;

/**
 * Opauth Guzzle client
 *
 * Works with Guzzle version 3.7 and 3.8, which require minimum PHP version 5.3.3. If you have PHP 5.4.2 or higher we
 * advice you to use GuzzleHttp adapter instead.
 *
 * Set 'http_client' => 'Opauth\\Opauth\\HttpClient\\Guzzle3' in your Opauth config array to use this adapter
 * Additionally your applications composer file should have in the require: "guzzle/guzzle": "~3.7"
 *
 * Guzzle client for Guzzle 3
 *
 */
class Guzzle3 implements HttpClientInterface
{

    /**
     * Response headers
     *
     * @var string
     */
    public $responseHeaders;

    /**
     * User agent
     *
     * @var string
     */
    public $userAgent = 'Opauth';

    /**
     * Makes a GET request
     *
     * @param string $url Destination URL
     * @param array $data Data to be submitted via GET
     * @return string Content resulted from request, without headers
     */
    public function get($url, $data = array())
    {
        $client = $this->getClient($url);
        $request = $client->get($url, array(), array('query' => $data));
        return $this->send($request);
    }

    /**
     * Makes a POST request
     *
     * @param string $url Destination URL
     * @param array $data Data to be POSTed
     * @return string Content resulted from request, without headers
     */
    public function post($url, $data)
    {
        $client = $this->getClient($url);
        $request = $client->post($url, array(), $data);
        return $this->send($request);
    }

    /**
     * Get a Guzzle Client instance
     *
     * @param string $url Base url for request
     * @return Client
     * @throws OpauthException
     */
    protected function getClient($url)
    {
        if (!class_exists('Guzzle\\Http\\Client')) {
            throw new OpauthException('Guzzle 3 not installed. Install or use other http_client');
        }
        $client = new Client($url);
        $client->setUserAgent($this->userAgent);
        return $client;
    }

    /**
     * Sends the Guzzle request
     *
     * @param RequestInterface $request
     * @return string Response body
     * @throws OpauthException
     */
    protected function send(RequestInterface $request)
    {
        try {
            $response = $request->send();
        } catch (GuzzleException $e) {
            throw new OpauthException('[Guzzle3][GuzzleException]' . $e->getMessage());
        }
        $this->responseHeaders = $response->getHeaderLines();
        return $response->getBody(true);
    }
}
