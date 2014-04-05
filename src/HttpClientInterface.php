<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth;

/**
 * HTTP client Interface
 *
 * HTTP client adapters MUST implement this
 *
 */
interface HttpClientInterface
{
    /**
     * Handles GET requests
     *
     * @param string $url
     * @param array $data
     */
    public function get($url, $data = array());

    /**
     * Handles POST requests
     *
     * @param string $url
     * @param array $data
     */
    public function post($url, $data);
}
