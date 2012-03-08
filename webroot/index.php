<?php
define('LIB', '../lib/Opauth/');

require LIB.'Opauth.php';
$Opauth = new Opauth( array(
	'debug' => true,
	'strategies' => array(
		'facebook'
	)
));