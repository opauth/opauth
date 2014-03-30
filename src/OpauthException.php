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
 * Opauth Exception
 */
class OpauthException extends \RuntimeException
{
    /**
     * String error code
     *
     * @var string
     */
    protected $code;

    /**
     * Related strategy
     *
     * @var string
     */
     protected $strategy;

     /**
      * Raw exception to aid debug
      * Usually raw HTTP response from provider
      *
      * @var mixed
      */
      protected $raw;

    /**
     * Constructor
     *
     * @param string $message Friendly error message
     * @param string $code Error code (eg. access_denied)
     * @param string $strategy Strategy from which the exception occured
     * @param mixed $raw Raw data to help in debug, usually raw HTTP response from provider
     */
    public function __construct($message, $code = null, $strategy = null, $raw = null)
    {
        if ($code) {
            $this->code = $code;
        }
        if ($strategy) {
            $this->strategy = $strategy;
        }
        if ($raw) {
            $this->raw = $raw;
        }

        parent::__construct($message);
    }
}
