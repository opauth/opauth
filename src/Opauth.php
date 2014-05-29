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
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 */
class Opauth
{

    /**
     * Strategy instance
     *
     * @var AbstractStrategy
     */
    protected $strategy;

    /**
     * Holds array of strategy settings indexed by url_name
     *
     * @var array
     */
    protected $strategies = array();

    /**
     * The request parser object
     *
     * @var ParserInterface
     */
    protected $requestParser;

    /**
     * The response object
     *
     * @var Response
     */
    public $response;

    /**
     * Configuration array
     *
     * @var array
     */
    protected $config = array(
        'http_client' => "Opauth\\Opauth\\HttpClient\\Curl",
        'callback' => 'callback',
        'path' => '/auth/'
    );

    /**
     * Constructor
     * Loads user configuration and strategies.
     *
     * @param array $config User configuration
     * @param ParserInterface $parser Request Parser instance
     */
    public function __construct($config = array(), ParserInterface $parser = null)
    {
        if (isset($config['Strategy'])) {
            $this->buildStrategies($config['Strategy']);
            unset($config['Strategy']);
        }
        $this->config = array_merge($this->config, $config);

        $this->setParser($parser);
    }

    /**
     * Get key from config array, null if not present
     *
     * @param string $key Configuration key
     * @return mixed Config value
     */
    public function config($key)
    {
        if (!isset($this->config[$key])) {
            return null;
        }
        return $this->config[$key];
    }

    /**
     * Run Opauth:
     * Parses request URI and perform defined authentication actions based based on it.
     * When running `request()` it will do a redirect, `callback` returns Opauth Response object
     *
     * @return null|Response Response object for callback
     * @throws OpauthException
     */
    public function run()
    {
        if (!$this->requestParser->urlname()) {
            throw new OpauthException('No strategy found in url');
        }

        $action = $this->requestParser->action();
        if (!$action) {
            $this->request();
            return null;
        }

        if ($action !== $this->config('callback')) {
            throw new OpauthException('Invalid callback url element: ' . $action);
        }
        return $this->callback();
    }

    /**
     * Calls request method on current strategy
     */
    public function request()
    {
        $this->getStrategy()->request();
    }

    /**
     * Callback method, executed after being redirected back
     *
     * @return Response
     * @throws OpauthException
     */
    public function callback()
    {
        $this->response = $this->getStrategy()->callback();
        if (!$this->response instanceof Response) {
            throw new OpauthException('Response should be instance of Opauth\\Opauth\\Response');
        }
        $this->response->map();
        if (!$this->response->isValid()) {
            throw new OpauthException('Invalid response, missing required parameters');
        }
        return $this->response;
    }

    /**
     * Setter method for request url parsing object
     *
     * @param ParserInterface $parser Request parser object, if null will use built-in Parser
     */
    protected function setParser(ParserInterface $parser = null)
    {
        if (!$parser) {
            $parser = new Request\Parser($this->config('path'));
        }
        $this->requestParser = $parser;
    }

    /**
     * Builds strategies
     *
     * @param array $strategies Array of strategies and their settings
     * @return true
     * @throws OpauthException
     */
    public function buildStrategies($strategies)
    {
        if (!$strategies || !is_array($strategies)) {
            throw new OpauthException('No strategies found');
        }

        foreach ($strategies as $name => $settings) {
            $this->buildStrategy($name, $settings);
        }
        return true;
    }

    /**
     * Builds single strategy array
     *
     * @param string|integer $name
     * @param array|string $settings
     * @return array Settings array
     */
    public function buildStrategy($name, $settings)
    {
        if (!is_array($settings)) {
            $name = $settings;
            $settings = array();
        }

        $settings['provider'] = $name;

        if (empty($settings['_name'])) {
            $settings['_name'] = 'Opauth\\' . $name . '\\Strategy\\' . $name;
        }

        // Define a URL-friendly name
        if (empty($settings['_url_name'])) {
            $settings['_url_name'] = strtolower($name);
        }
        if (!isset($settings['_enabled'])) {
            $settings['_enabled'] = true;
        }

        return $this->strategies[$settings['_url_name']] = $settings;
    }

    /**
     * Sets Strategy instance
     *
     * @param AbstractStrategy $strategy
     */
    public function setStrategy(AbstractStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Get strategy, if not set load strategy based on url name
     *
     * @return AbstractStrategy
     */
    public function getStrategy()
    {
        if (empty($this->strategy)) {
            $this->loadStrategy();
        }
        return $this->strategy;
    }

    /**
     * Loads strategy based on url if not manually set
     *
     * @throws OpauthException
     */
    protected function loadStrategy()
    {
        if (!$this->strategies) {
            throw new OpauthException('No strategies configured');
        }

        $urlname = $this->requestParser->urlname();
        if (!array_key_exists($urlname, $this->strategies)) {
            throw new OpauthException('Unsupported or undefined Opauth strategy - ' . $urlname);
        }

        $settings = $this->strategies[$urlname];
        if (!$settings['_enabled']) {
            throw new OpauthException('This strategy is not enabled');
        }
        $classname = $settings['_name'];

        if (!class_exists($classname, true)) {
            throw new OpauthException(sprintf('Strategy class %s not found', $classname));
        }

        $callbackUrl = $this->requestParser->providerUrl() . '/' . $this->config('callback');
        $Client = $this->config('http_client');
        $this->setStrategy(new $classname($settings, $callbackUrl, new $Client));
    }
}
