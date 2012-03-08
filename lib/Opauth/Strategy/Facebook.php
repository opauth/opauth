<?php
class Facebook extends OpauthStrategy{
	
/**
 * Compulsory config keys
 */
	public $expects = array('app_id', 'app_secret');
	
/**
 * Optional config keys with respective default values
 */
	public $defaults = array(
		'scope' => null
	);
	
	public function __construct(&$Opauth, $strategy){
		parent::__construct($Opauth, $strategy);
		
		$this->expects('app_id');
		$this->expects('app_id');
	}
}