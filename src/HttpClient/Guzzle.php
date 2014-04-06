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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Opauth\Opauth\HttpClientInterface;
use Opauth\Opauth\OpauthException;

/**
 * Opauth Guzzle client
 *
 * Works with Guzzle version 4, which require minimum PHP version 5.4.2. If you use an older version you can use Guzzle
 * adapter instead.
 *
 * Set 'http_client' => 'Opauth\\Opauth\\HttpClient\\Guzzle' in your Opauth config array to use this adapter.
 * Additionally your applications composer file should have in the require: "guzzlehttp/guzzle": "~4.0"
 *
 * Guzzle client for Guzzle 4
 *
 */
class Guzzle implements HttpClientInterface
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
     * @throws OpauthException
     */
    public function get($url, $data = array())
    {
        $client = $this->getClient($url);
        try {
            $response = $client->get($url, array('query' => $data));
        } catch (TransferException $e) {
            throw new OpauthException('[Guzzle][TransferException]' . $e->getMessage());
        }
        $this->responseHeaders = $response->getHeaders();
        return $response->getBody();
    }

    /**
     * Makes a POST request
     *
     * @param string $url Destination URL
     * @param array $data Data to be POSTed
     * @return string Content resulted from request, without headers
     * @throws OpauthException
     */
    public function post($url, $data)
    {
        $client = $this->getClient($url);
        try {
            $response = $client->post($url, array('body' => $data));
        } catch (TransferException $e) {
            throw new OpauthException('[Guzzle][TransferException]' . $e->getMessage());
        }
        $this->responseHeaders = $response->getHeaders();
        return $response->getBody();
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
        if (!class_exists('GuzzleHttp\\Client')) {
            throw new OpauthException('Guzzle 4 not installed. Install or use other http_client');
        }
        $config = array(
            'base_url' => $url,
            'defaults' => array(
                'headers' => array(
                    'User-Agent' => $this->userAgent
                )
            )
        );
        $client = new Client($config);
        return $client;
    }
}
