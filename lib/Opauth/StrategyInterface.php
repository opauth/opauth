<?php
/**
 * Opauth Strategy
 * Individual strategies should immplement this interface
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.Strategy
 * @license      MIT License
 */
namespace Opauth;

/**
 * Opauth StrategyInterface
 * Individual strategies should implement this interface
 *
 * @package			Opauth.StrategyInterface
 */
interface StrategyInterface {

	public function request();

	public function callback();

}
