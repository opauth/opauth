<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2013 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Opauth\Transport;

use Opauth\Opauth\Transport\Base;

/**
 * Opauth Curl
 * File transport class, uses file_get_contents for environments where curl is not available
 *
 * @package      Opauth
 */
class File extends Base {

	/**
	 * Makes a request using file_get_contents
	 *
	 * @param string $url
	 * @param array $options
	 * @return string Response body
	 * @throws \Exception
	 */
	protected function request($url, $options = array()) {
		if (!ini_get('allow_url_fopen')) {
			throw new \Exception('file_get_contents not allowed, try using other http_client_method such as curl');
		}
		$context = null;
		if (!empty($options)) {
			$context = stream_context_create($options);
		}
		$content = file_get_contents($url, false, $context);
		$this->responseHeaders = implode("\r\n", $http_response_header);
		return $content;
	}

}