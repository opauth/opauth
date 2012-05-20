<?php
/**
 * Callback for Opauth
 * 
 * This file (callback.php) provides an example on how to properly receive auth response of Opauth.
 * 
 * Basic steps:
 * 1. Fetch auth response based on callback transport parameter in config.
 * 2. Validate auth response
 * 3. Once auth response is validated, your PHP app should then work on the auth response 
 *    (eg. registers or logs user in to your site, save auth data onto database, etc.)
 * 
 */


/**
 * Define paths
 */
define('OPAUTH_EXAMPLE', dirname(__FILE__).'/');
define('OPAUTH_LIB', dirname(OPAUTH_EXAMPLE).'/lib/Opauth/');

/**
* Load config
*/
if (!file_exists(OPAUTH_EXAMPLE.'opauth.conf.php')){
	trigger_error('Config file missing at '.OPAUTH_EXAMPLE.'opauth.conf.php', E_USER_ERROR);
	exit();
}
require OPAUTH_EXAMPLE.'opauth.conf.php';

/**
 * Instantiate Opauth with the loaded config but not run automatically
 */
require OPAUTH_LIB.'opauth.php';
$Opauth = new Opauth( $config, false );

	
/**
* Fetch auth, based on transport configuration for callback
*/
$response = null;

switch($Opauth->env['callback_transport']){	
	case 'session':
		session_start();
		$response = $_SESSION['opauth'];
		unset($_SESSION['opauth']);
		break;
	case 'post':
		$response = unserialize(base64_decode( $_POST['opauth'] ));
		break;
	case 'get':
		$response = unserialize(base64_decode( $_GET['opauth'] ));
		break;
	default:
		echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
		break;
}

/**
 * Check if it's an error callback
 */
if (array_key_exists('error', $response)){
	echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.'."<br>\n";
}

/**
 * No it isn't. Proceed with auth validation
 */
else{
	if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])){
		echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.'."<br>\n";
	}
	elseif (!$Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)){
		echo '<strong style="color: red;">Invalid auth response: </strong>'.$reason.".<br>\n";
	}
	else{
		echo '<strong style="color: green;">OK: </strong>Auth response is validated.'."<br>\n";

		/**
		 * It's all good. Go ahead with your application-specific authentication logic
		 */
	}
}


/**
* Auth response dump
*/
echo "<pre>";
print_r($response);
echo "</pre>";