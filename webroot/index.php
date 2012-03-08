<?php
/**
 * Opauth webroot catchall
 * - Only required if you are sending traffic directly to Opauth's webroot
 * - If you instantiate Opauth directly from your app or PHP framework, this will not be run.
 * 
 */

/**
 * Define paths
 */
define('OPAUTH_WEBROOT', dirname(__FILE__).'/');
define('OPAUTH_ROOT', dirname(OPAUTH_WEBROOT).'/');
define('OPAUTH_LIB', OPAUTH_ROOT.'lib/Opauth/');

/**
 * Load config
 */
if (!file_exists(OPAUTH_WEBROOT.'opauth.conf.php')){
	trigger_error('Config file missing at '.OPAUTH_WEBROOT.'opauth.conf.php', E_USER_ERROR);
	exit();
}
require OPAUTH_WEBROOT.'opauth.conf.php';

/**
 * Instantiate Opauth with the loaded config
 */
require OPAUTH_LIB.'opauth.php';
$Opauth = new Opauth( $config );