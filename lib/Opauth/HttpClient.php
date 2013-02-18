<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth;
use \Exception;

/**
 * Opauth HttpClient
 * Very simple httpclient using file_get_contents or curl
 *
 * @package      Opauth
 */
class HttpClient {

	/**
	 * Response headers
	 *
	 * @var string
	 */
	public static $responseHeaders;

	/**
	 * Method to use, currently supports 'curl' for curl and 'file' for file_get_contents
	 *
	 * @var string
	 */
	public static $method = 'curl';

	/**
	 * Client redirect: This function builds the full HTTP URL with parameters and redirects via Location header.
	 *
	 * @param string $url Destination URL
	 * @param array $data Data
	 * @param boolean $exit Whether to call exit() right after redirection
	 */
	public static function redirect($url, $data = array(), $exit = true) {
		if ($data) {
			$url .= '?' . self::buildQuery($data);
		}
		header("Location: $url");
		if ($exit) {
			exit();
		}
	}

	/**
	 * Basic HTTP GET request via self::request(), wrapper of file_get_contents/curl
	 *
	 * @param string $url Destination URL
	 * @param array $data Data to be submitted via GET
	 * @return string Content resulted from request, without headers
	 */
	public static function get($url, $data = array()) {
		if ($data) {
			$url .= '?' . self::buildQuery($data);
		}
		return self::request($url);
	}

	/**
	 * Basic HTTP POST request via self::request(), wrapper of file_get_contents/curl
	 *
	 * @param string $url Destination URL
	 * @param array $data Data to be POSTed
	 * @return string Content resulted from request, without headers
	 */
	public static function post($url, $data) {
		$query = self::buildQuery($data);

		$stream = array('http' => array(
			'method' => 'POST',
			'header' => "Content-type: application/x-www-form-urlencoded",
			'content' => $query
		));

		return self::request($url, $stream);
	}

	/**
	 * Simple HTTP request with file_get_contents or curl, see HttpClient::$method
	 * Provides basic HTTP calls.
	 * See get() and post() for wrapper functions of request()
	 *
	 * Notes:
	 * Strategy is to provide own HTTP transport handler if requiring more advanced support.
	 *
	 * @param string $url Full URL to load
	 * @param array $options Stream context options (http://php.net/stream-context-create)
	 * @return string Content resulted from request, without headers
	 */
	public static function request($url, $options = array()) {
		if (self::$method == 'file') {
			return self::fileRequest($url, $options);
		}
		return self::curlRequest($url, $options);
	}

	/**
	 * Makes a request using file_get_contents, set HttpClient::$method = 'file' to use this
	 *
	 * @param string $url
	 * @param array $options
	 * @return string Response body
	 */
	protected static function fileRequest($url, $options) {
		if (!ini_get('allow_url_fopen')) {
			throw new Exception('file_get_contents not allowed, try using other http_client_method such as curl');
		}
		$context = null;
		if (!empty($options)) {
			$context = stream_context_create($options);
		}
		$content = file_get_contents($url, false, $context);
		self::$responseHeaders = implode("\r\n", $http_response_header);
		return $content;
	}

	/**
	 * Makes a request using curl, set HttpClient::$method = 'curl' to use this (default)
	 *
	 * @param string $url
	 * @param array $options
	 * @return string Response body
	 */
	protected static function curlRequest($url, $options) {
		if (!function_exists('curl_init')) {
			throw new Exception('Curl not supported, try using other http_client_method such as file');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		if (!empty($options['http']['method']) && strtoupper($options['http']['method']) === 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $options['http']['content']);
		}
		$content = curl_exec($ch);
		curl_close($ch);
		list($headers, $content) = explode("\r\n\r\n", $content, 2);
		self::$responseHeaders = $headers;
		return $content;
	}

	/**
	 * Helper method to build the query string
	 *
	 * @param array $data
	 * @return string Query string
	 */
	protected static function buildQuery($data) {
		return http_build_query($data, '', '&');
	}

}