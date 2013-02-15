<?php
namespace Opauth;

class HttpClient {

	public static $responseHeaders;

	public static function buildQuery($data) {
		return http_build_query($data, '', '&');
	}

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
	 * Basic HTTP GET request via self::request(), wrapper of file_get_contents
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
	 * Basic HTTP POST request via self::request(), wrapper of file_get_contents
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
	 * Simple HTTP request with file_get_contents
	 * Provides basic HTTP calls.
	 * See get() and post() for wrapper functions of request()
	 *
	 * Notes:
	 * Reluctant to use any more advanced transport like cURL for the time being to not
	 *     having to set cURL as being a requirement.
	 * Strategy is to provide own HTTP transport handler if requiring more advanced support.
	 *
	 * @param string $url Full URL to load
	 * @param array $options Stream context options (http://php.net/stream-context-create)
	 * @return string Content resulted from request, without headers
	 */
	public static function request($url, $options = array()) {
		$context = null;
		if (!empty($options) && is_array($options)) {
			$context = stream_context_create($options);
		}

		$content = @file_get_contents($url, false, $context);
		self::$responseHeaders = implode("\r\n", $http_response_header);

		return $content;
	}

}