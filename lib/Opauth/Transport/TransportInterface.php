<?php
namespace Opauth\Transport;

interface TransportInterface {

	public function redirect($url, $data = array(), $exit = true);

	public function get($url, $data = array());

	public function post($url, $data);

}