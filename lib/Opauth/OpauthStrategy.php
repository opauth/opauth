<?php
/**
 * Opauth Strategy
 * - Individual strategies to be extended from this class
 *
 */
class OpauthStrategy{
	public $Opauth;
	
	public function __construct(&$Opauth){
		$this->Opauth = $Opauth;
		$this->Opauth->debug('hey');
	}
	
	public function request(){
	}
	
	public function callback(){
	}
}