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
     * @param TransportInterface $transport
     */
    public function __construct($config, $callbackUrl, TransportInterface $transport);
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
}
