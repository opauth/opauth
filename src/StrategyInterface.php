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
 * Opauth StrategyInterface
 * Individual strategies should implement this interface
 *
 */
interface StrategyInterface
{
    /**
     * @param array $config
     * @param string $callbackUrl
     * @param HttpClientInterface $client
     */
    public function __construct($config, $callbackUrl, HttpClientInterface $client);

    /**
     * Handles initial authentication request
     *
     */
    public function request();

    /**
     * Handles callback from provider
     *
     * @return Response Opauth Response object
     */
    public function callback();

    /**
     * Builds the full HTTP URL with parameters and redirects via Location header.
     *
     * @param string $url Destination URL
     * @param array $data Data
     * @param boolean $exit Whether to call exit() right after redirection
     */
    public function redirect($url, $data = array(), $exit = true);
}
