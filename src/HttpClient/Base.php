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

use Opauth\Opauth\HttpClientInterface;

/**
 * Opauth Base client
 * Base class for built in Curl and File client classes
 *
 */
abstract class Base implements HttpClientInterface
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
     * Makes a request, child classes must implement this logic
     *
     * @param string $url
     * @param array $options
     * @return string Response body
     */
    abstract protected function request($url, $options = array());

    /**
     * Basic HTTP GET request via request(), wrapper of file_get_contents/curl
     *
     * @param string $url Destination URL
     * @param array $data Data to be submitted via GET
     * @return string Content resulted from request, without headers
     */
    public function get($url, $data = array())
    {
        if ($data) {
            $url .= '?' . $this->buildQuery($data);
        }
        $stream = array(
            'http' => array(
                'header' => "User-Agent: " . $this->userAgent
            )
        );

        return $this->request($url, $stream);
    }

    /**
     * Basic HTTP POST request via request(), wrapper of file_get_contents/curl
     *
     * @param string $url Destination URL
     * @param array $data Data to be POSTed
     * @return string Content resulted from request, without headers
     */
    public function post($url, $data)
    {
        $query = $this->buildQuery($data);

        $stream = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\nUser-Agent: " . $this->userAgent,
                'content' => $query
            )
        );

        return $this->request($url, $stream);
    }

    /**
     * Helper method to build the query string
     *
     * @param array $data
     * @return string Query string
     */
    protected function buildQuery($data)
    {
        return http_build_query($data, '', '&');
    }
}
