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
	}
	
	public function request(){
	}
	
	public function callback(){
	}
}