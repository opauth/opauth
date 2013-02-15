<?php

namespace Opauth;

class Request {

	public $provider = null;

	public $action = null;

	public $path = '/auth/';

	public function __construct($path = null) {
		if ($path) {
			$this->path = $path;
		}
		$this->parseUri();
	}

	private function parseUri() {
		$request = substr($_SERVER['REQUEST_URI'], strlen($this->path) - 1);

		preg_match_all('/\/([A-Za-z0-9-_]+)/', $request, $matches);
		if (!empty($matches[1][0])) {
			$this->provider = $matches[1][0];
		}
		if (!empty($matches[1][1])) {
			$this->action = $matches[1][1];
		}
	}

	public function getHost() {
		return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
	}

	public function providerUrl() {
		return $this->getHost() . $this->path . $this->provider;
	}
}