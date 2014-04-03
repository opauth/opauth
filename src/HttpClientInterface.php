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
 * Http client Interface
 * Http client adapters should implement this
 *
 */
interface HttpClientInterface
{

    /**
     * Redirect method
     *
     * @param string $url
     * @param array $data
     * @param boolean $exit
     */

    public function redirect($url, $data = array(), $exit = true);

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
