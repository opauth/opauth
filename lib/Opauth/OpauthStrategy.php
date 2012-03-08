<?php
/**
 * Opauth Strategy
 * - Individual strategies to be extended from this class
 *
 */
class OpauthStrategy{
	protected $Opauth;
	protected $strategy;
	
	public function __construct(&$Opauth, $strategy){
		$this->Opauth = $Opauth;
		$this->strategy = $strategy;
	}
	
	public function request(){
	}
	
	public function callback(){
	}
}