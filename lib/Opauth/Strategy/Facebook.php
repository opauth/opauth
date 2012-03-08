<?php
class Facebook extends OpauthStrategy{
	
	public function __construct(&$Opauth, $strategy){
		parent::__construct($Opauth, $strategy);
		
	}
}