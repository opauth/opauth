<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2014 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Request;

use Opauth\Opauth\ParserInterface;
use Opauth\Opauth\OpauthException;

/**
 * Opauth Request Parser
 * Parses current request url parameters
 *
 */
class Parser implements ParserInterface
{

    /**
     * Strategy urlname, used to switch to correct strategy
     *
     * @var string
     */
    protected $urlname;

    /**
     * Action, null for request, 'callback' for callback
     *
     * @var string
     */
    protected $action;

    /**
     * Opauth url path, relative to host
     *
     * @var string
     */
    protected $path;

    /**
     * Set path if '/auth/' isn't the default path, or if application is in a subdirectory
     *
     * @param string $path
     */
    public function __construct($path = '/')
    {
        $this->path = $path;
        $this->parseUri();
    }

    /**
     * Get strategy url_name and action form the request
     *
     * @throws OpauthException
     */
    protected function parseUri()
    {
        if (strpos($_SERVER['REQUEST_URI'], $this->path) === false) {
            throw new OpauthException('Not an Opauth request, path is not in uri');
        }
        $request = substr($_SERVER['REQUEST_URI'], strlen($this->path) - 1);

        preg_match_all('/\/([A-Za-z0-9-_]+)/', $request, $matches);
        if (!empty($matches[1][0])) {
            $this->urlname = $matches[1][0];
        }
        if (!empty($matches[1][1])) {
            $this->action = $matches[1][1];
        }
    }

    public function action()
    {
        return $this->action;
    }

    public function urlname()
    {
        return $this->urlname;
    }

    /**
     * getHost
     *
     * @return string Full host string
     */
    protected function getHost()
    {
        return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * providerUrl
     *
     * @return string Full path to provider url_name
     */
    public function providerUrl()
    {
        return $this->getHost() . $this->path . $this->urlname;
    }
}
