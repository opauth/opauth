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
 * Curl transport class
 *
 * @package      Opauth
 */
class Curl extends Base {

	/**
	 * Makes a request using curl
	 *
	 * @param string $url
	 * @param array $options
	 * @return string Response body
	 * @throws \Exception
	 */
	protected function request($url, $options = array()) {
		if (!function_exists('curl_init')) {
			throw new \Exception('Curl not supported, use other http transport');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Opauth');
		if (!empty($options['http']['method']) && strtoupper($options['http']['method']) === 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $options['http']['content']);
		}
		$content = curl_exec($ch);
		$errno = curl_errno($ch);
		if ($errno !== 0) {
			$msg = curl_error($ch);
			throw new \Exception($msg);
		}
		curl_close($ch);
		list($headers, $content) = explode("\r\n\r\n", $content, 2);
		$this->responseHeaders = $headers;
		return $content;
	}

}