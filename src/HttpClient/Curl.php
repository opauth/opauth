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

use Opauth\Opauth\OpauthException;

/**
 * Opauth Curl
 * Curl client class
 *
 */
class Curl extends Base
{

    /**
     * Makes a request using curl
     *
     * @param string $url
     * @param array $options
     * @return string Response body
     * @throws \Exception
     */
    protected function request($url, $options = array())
    {
        if (!function_exists('curl_init')) {
            throw new OpauthException('Curl not supported, use other http_client in your config');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Opauth');
        if (!empty($options['http']['method']) && strtoupper($options['http']['method']) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $options['http']['content']);
        }
        $content = curl_exec($ch);
        $errno = curl_errno($ch);
        if ($errno !== 0) {
            $msg = curl_error($ch);
            throw new OpauthException($msg);
        }
        curl_close($ch);
        list($headers, $content) = explode("\r\n\r\n", $content, 2);
        $this->responseHeaders = $headers;
        return $content;
    }
}
