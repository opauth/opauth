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
 * Parser Interface
 * Parser classes should implement this
 *
 */
interface ParserInterface
{

    public function __construct($path = '/');

    /**
     * Getter for provider's urlname
     *
     * @return string Request parameter for the provider urlname
     */
    public function urlname();

    /**
     * Getter for action argument, usually `callback`
     *
     * @return string Request parameter for action
     */
    public function action();

    /**
     * Returns base provider url
     *
     * @return string Url string to base path for the provider
     */
    public function providerUrl();
}
