<?php
/**
 * Include this file in your application to get autoloading when not using composer.
 *
 * require '/path/to/opauth/autoload.php';
 *
 * then you can create Opauth instance with:
 * new Opauth\Opauth();
 */

require __DIR__ . '/lib/Opauth/AutoLoader.php';
Opauth\AutoLoader::register('Opauth');

/**
 * Optionally uncomment the lines below and set the absolute path for strategy directory
 * Alternative for passing strategy_dir in Opauth constructor config array.
 *
 * Not required when using composer or when strategies are placed in lib/Opauth/Strategy
 */

//$strategyDir = __DIR__ . DIRECTORY_SEPARATOR . 'strategies';
//Opauth\AutoLoader::register('Opauth\\Strategy', $strategyDir);
